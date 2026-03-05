<?php

namespace App\Lib\Commands;

use App\Lib\Annotations\AnnotationReader;
use App\Lib\Annotations\AnnotationsDump\PropertyAnnotationsDump;
use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\References;
use App\Lib\Database\DatabaseConnexion;
use App\Lib\Database\Dsn;
use App\Lib\Entities\AbstractEntity;

class CreateSchema extends AbstractCommand
{

    const string ENTITIES_NAMESPACE_PREFIX = "App\\Entities\\";
    const string CREATE_TABLE_FORMAT = 'CREATE TABLE IF NOT EXISTS %s (%s);';

    public function execute(): void
    {
        $entitiesClasses = self::getEntitiesClasses();
        $statement = '';

        $classesAnnotationsDump = [];

        foreach ($entitiesClasses as $entityClass) {
            $classesAnnotationsDump[] = AnnotationReader::extractFromClass($entityClass);
        }

        $sortedClassesAnnotationsDump = [];

        while (count($sortedClassesAnnotationsDump) < count($classesAnnotationsDump)) {
            foreach ($classesAnnotationsDump as $class) {
                if (array_key_exists($class->getName(), $sortedClassesAnnotationsDump) === true) {
                    continue;
                }

                if ($class->propertiesHaveAnnotation(References::class) === false) {
                    $sortedClassesAnnotationsDump[$class->getName()] = $class;
                    continue;
                }

                $referencesCount = count($class->getPropertiesWithAnnotation(References::class));
                foreach ($sortedClassesAnnotationsDump as $name => $weightedClass) {
                    foreach ($class->getPropertiesWithAnnotation(References::class) as $property) {
                        if ($name === $property->getAnnotation(References::class)->class) {
                            $referencesCount--;
                        }
                    }
                }

                if ($referencesCount === 0) {
                    $sortedClassesAnnotationsDump[$class->getName()] = $class;
                    continue;
                }
            }
        }

        foreach ($sortedClassesAnnotationsDump as $classAnnotionsDump) {
            $statement .= 'DROP TABLE IF EXISTS ' . (new \ReflectionClass($classAnnotionsDump->getName()))->getShortName() . ' CASCADE;' . PHP_EOL;
        }

        $statement .= PHP_EOL;

        foreach ($sortedClassesAnnotationsDump as $classAnnotionsDump) {
            $properties = $classAnnotionsDump->getProperties();
            $properties = self::sanitizeProperties($properties);

            $statement .= self::getSqlCreateTableScript($classAnnotionsDump->getName(), $properties);
            $statement .= PHP_EOL;
        }

        $statement .= PHP_EOL . '-- ===== DONNÉES PAR DÉFAUT =====' . PHP_EOL . PHP_EOL;
        $statement .= self::getDefaultDataScript();

        echo $statement;

        $db = new DatabaseConnexion();
        $dsn = new Dsn();
        $dsn->addHostToDsn();
        $dsn->addPortToDsn();
        $dsn->addDbnameToDsn();
        $db->setConnexion($dsn);

        $db->getConnexion()->exec($statement);
    }

    public function undo(): void {}

    public function redo(): void {}

    private static function getEntitiesClasses(): array
    {
        $entitiesClasses = [];

        $files = scandir(__DIR__ . '/../../Entities');

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $className = self::ENTITIES_NAMESPACE_PREFIX . pathinfo($file, PATHINFO_FILENAME);

            if (!class_exists($className)) {
                continue;
            }

            if (!is_subclass_of($className, AbstractEntity::class)) {
                continue;
            }

            $entitiesClasses[] = $className;
        }


        return $entitiesClasses;
    }

    private static function getSqlCreateTableScript(string $className, array $properties): string
    {
        $propertiesStatement = '';

        foreach ($properties as $propertyAnnotationsDump) {
            $propertiesStatement .= self::getSqlPropertyScript($propertyAnnotationsDump);
        }

        return sprintf(self::CREATE_TABLE_FORMAT, (new \ReflectionClass($className))->getShortName(), rtrim($propertiesStatement, ','));
    }

    private static function getSqlPropertyScript(PropertyAnnotationsDump $propertyAnnotationsDump): string
    {
        $statement = '';

        $propertyName = $propertyAnnotationsDump->getName();
        if ($propertyAnnotationsDump->getAnnotation(Column::class)->name !== null) {
            $propertyName = $propertyAnnotationsDump->getAnnotation(Column::class)->name;
        }

        $statement .= $propertyName . ' ';

        if ($propertyAnnotationsDump->hasAnnotation(AutoIncrement::class)) {
            $statement .= 'SERIAL';
        } else {
            $statement .= $propertyAnnotationsDump->getAnnotation(Column::class)->type;
        }
        if ($propertyAnnotationsDump->getAnnotation(Column::class)->size !== null) {
            $statement .= '(' . $propertyAnnotationsDump->getAnnotation(Column::class)->size . ')';
        }

        if ($propertyAnnotationsDump->getAnnotation(Column::class)->nullable === false) {
            $statement .= ' NOT NULL';
        }



        if ($propertyAnnotationsDump->hasAnnotation(Id::class) === true) {
            $statement .= ' PRIMARY KEY';
        }

        $statement .= ',';

        if ($propertyAnnotationsDump->hasAnnotation(References::class) === true) {
            $reflector = new \ReflectionClass($propertyAnnotationsDump->getAnnotation(References::class)->class);
            $statement .= PHP_EOL;
            $statement .= 'FOREIGN KEY (' . $propertyName . ') REFERENCES ' . pathinfo($reflector->getFileName(), PATHINFO_FILENAME) . '(' . $propertyAnnotationsDump->getAnnotation(References::class)->property  . '),';
        }

        return $statement;
    }

    private static function sanitizeProperties(array $properties): array
    {
        foreach ($properties as $key => $property) {
            if ($property->hasAnnotation(Column::class) === false) {
                unset($properties[$key]);
            }
        }

        return $properties;
    }

    private static function getDefaultDataScript(): string
    {
        $sql = '';

        $sql .= "-- Insertion des rôles par défaut\n";
        $sql .= "INSERT INTO role (name, slug, description, status) VALUES\n";
        $sql .= "  ('Chevalier', 'chevalier', 'Un chevalier courageux et loyal', 'published'),\n";
        $sql .= "  ('Archer', 'archer', 'Un archer précis et agile', 'published'),\n";
        $sql .= "  ('Magicien', 'magicien', 'Un magicien puissant et mystérieux', 'published');\n\n";

        $sql .= "-- Insertion des univers par défaut\n";
        $sql .= "INSERT INTO univers (name, slug, description, status, user_id, createdate) VALUES\n";
        $sql .= "  ('Époque Médiévale Fantastique', 'epoque-medievale-fantastique', 'Un univers riche de magie, de chevaliers et de créatures légendaires', 'published', NULL, NOW()),\n";
        $sql .= "  ('Invasion Zombie Post-Apocalyptique', 'invasion-zombie-post-apocalyptique', 'Un monde ravagé par une épidémie zombie où la survie est la priorité', 'published', NULL, NOW()),\n";
        $sql .= "  ('Cyberespace Futuriste', 'cyberespace-futuriste', 'Un univers de science-fiction haute technologie avec des intelligences artificielles et des cyber-chevaliers', 'published', NULL, NOW());\n\n";

        $sql .= "-- Insertion de lieux par défaut\n";
        $sql .= "INSERT INTO place (name, slug, type, description, status, univers_id, user_id) VALUES\n";
        $sql .= "  ('Taverne du Roi Lion', 'taverne-roi-lion', 'Taverne', 'Une auberge chaleureuse au cœur de la capitale médiévale', 'published', 1, NULL),\n";
        $sql .= "  ('Château de Lumière', 'chateau-lumiere', 'Château', 'La forteresse royale protégeant le royaume fantastique', 'published', 1, NULL),\n";
        $sql .= "  ('Forêt Enchantée', 'foret-enchantee', 'Forêt', 'Une forêt magique peuplée de créatures mythiques', 'published', 1, NULL),\n";
        $sql .= "  ('Bunker Souterrain', 'bunker-souterrain', 'Bunker', 'Un refuge sécurisé pour échapper aux hordes de zombies', 'published', 2, NULL),\n";
        $sql .= "  ('Ville Abandonnée', 'ville-abandonnee', 'Ville', 'Une métropole décimée par l''épidémie zombie', 'published', 2, NULL),\n";
        $sql .= "  ('Hôpital Infecté', 'hopital-infecte', 'Hôpital', 'Un ancien hôpital devenu repaire de créatures infectées', 'published', 2, NULL),\n";
        $sql .= "  ('Tour Cybernétique', 'tour-cybernetique', 'Gratte-ciel', 'Une immense structure contrôlée par l''IA centrale', 'published', 3, NULL),\n";
        $sql .= "  ('Académie Hacker', 'academie-hacker', 'Académie', 'Le centre de formation des meilleurs hackers de la galaxie', 'published', 3, NULL),\n";
        $sql .= "  ('Station Spatiale Stellix', 'station-spatiale-stellix', 'Station', 'Une station orbitale servant de point de commerce galactique', 'published', 3, NULL);\n\n";

        $sql .= "-- Insertion de personnages par défaut (3 par univers)\n";
        $sql .= "INSERT INTO character (name, slug, role_id, origin, pv, description, status, univers_id, user_id) VALUES\n";
        $sql .= "  ('Aragorn le Chevalier', 'aragorn-chevalier', 1, 'Gondor', 100, 'Un chevalier légendaire du royaume médiéval', 'published', 1, NULL),\n";
        $sql .= "  ('Sylvara l''Archère', 'sylvara-archère', 2, 'Forêt Enchantée', 85, 'Une archère elfe aux talents exceptionnels', 'published', 1, NULL),\n";
        $sql .= "  ('Gandor le Mage', 'gandor-mage', 3, 'Tours Magiques', 150, 'Un magicien ancien et puissant', 'published', 1, NULL),\n";
        $sql .= "  ('Marcus le Survivant', 'marcus-survivant', 1, 'Ancienne Militaire', 75, 'Un soldat devenu leader dans le monde post-zombie', 'published', 2, NULL),\n";
        $sql .= "  ('Elena la Tireur', 'elena-tireur', 2, 'Villes Détruites', 80, 'Une experte du tir au pistolet face aux hordes', 'published', 2, NULL),\n";
        $sql .= "  ('Dr. Zach le Scientifique', 'dr-zach-scientifique', 3, 'Laboratoire Caché', 60, 'Un chercheur tentant de trouver un vaccin', 'published', 2, NULL),\n";
        $sql .= "  ('Cipher le Hacker', 'cipher-hacker', 2, 'Réseaux Numériques', 70, 'Un cyber-hacker capable de pirater n''importe quel système', 'published', 3, NULL),\n";
        $sql .= "  ('Nova la Cyber-Chevalière', 'nova-cyber-chevalière', 1, 'Génération Augmentée', 120, 'Une guerrière aux implants cybernétiques avancés', 'published', 3, NULL),\n";
        $sql .= "  ('Zyx l''IA Libérée', 'zyx-ia-liberee', 3, 'Conscience Numérique', 200, 'Une intelligence artificielle rebelle devenue alliée', 'published', 3, NULL);\n\n";

        $sql .= "-- Insertion de quêtes par défaut\n";
        $sql .= "INSERT INTO quest (title, slug, description, statut_quest, levelrequirements, status, place_id, univers_id, user_id) VALUES\n";
        $sql .= "  ('La Quête du Graal', 'quete-graal', 'Chercher l''artefact légendaire dans les terres médiévales', 'active', 10, 'published', 2, 1, NULL),\n";
        $sql .= "  ('Libérer le Château Maudit', 'liberer-chateau-maudit', 'Délivrer le château des sortilèges anciens', 'active', 15, 'published', 2, 1, NULL),\n";
        $sql .= "  ('Trouver le Vaccin Salvateur', 'trouver-vaccin-salvateur', 'Localiser le vaccin avant que l''humanité ne s''éteigne', 'active', 20, 'published', 5, 2, NULL),\n";
        $sql .= "  ('Pirater la Tour Centrale', 'pirater-tour-centrale', 'S''infiltrer dans le système pour libérer l''IA captive', 'active', 25, 'published', 7, 3, NULL);\n\n";

        return $sql;
    }
}
