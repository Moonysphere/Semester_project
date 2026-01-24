<?php


namespace App\Lib\Commands;

use App\Lib\Database\DatabaseConnexion;
use App\Lib\Database\Dsn;


class CreateDatabase extends AbstractCommand
{

    public function execute(): void
    {
        $dsn = new Dsn();
        $dbName = $dsn->getDbName();
        $pdoDsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=postgres',
            $dsn->getHost(),
            $dsn->getPort()
        );
        $pdo = new \PDO($pdoDsn, $dsn->getUser(), $dsn->getPassword());
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = :dbname");
        $stmt->execute(['dbname' => $dbName]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            echo "La base de données '{$dbName}' existe déjà.\n";
            return;
        }

        $pdo->exec("CREATE DATABASE {$dbName};");

        echo "Base de données '{$dbName}' créée avec succès.\n";
    }

    public function undo(): void {}

    public function redo(): void {}
}
