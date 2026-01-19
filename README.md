# Projet semestriel

## < future contenue >

## by :

- **Fritzi FROIES**
- **Baptiste ROY**
- **Enzo MOITA**
- **Axel BARBELLION**

## Lancement du projet

### 1. Démarrage des conteneurs Docker

Dans le dossier /Lorebase :

```bash
docker compose up -d --build
```

### 2. Accès au conteneur PHP

```bash
docker exec -it php-framework-php sh
```

### 3. Création de la base de données et du schéma

Dans le shell du conteneur, exécutez :

```bash
php /var/www/html/bin/console.php -c CreateDatabase
```

Permet de lancer la base de donnée si elle n'existe pas.

```bash
php /var/www/html/bin/console.php -c CreateSchema
```

Permet de construire les tables de la base de donnée.
