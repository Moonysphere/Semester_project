# Projet semestriel

## < future contenue >

## by :

- **Fritzi FROIES**
- **Baptiste ROY**
- **Enzo MOITA**
- **Axel BARBELLION**

## Lancement du projet

### 1. Démarrage des conteneurs Docker

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
php /var/www/html/bin/console.php -c CreateSchema
```
