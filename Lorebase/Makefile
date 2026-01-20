# for psql shell
psql-shell:
	docker exec -it php-framework-postgres psql -U user -d db

# for docker shell
docker-shell:
	docker exec -it php-framework-php sh

# for create database
create-base:
	docker exec -it php-framework-php php /var/www/html/bin/console.php -c CreateDatabase

# for add schema
inject-schema:
	docker exec -it php-framework-php php /var/www/html/bin/console.php -c CreateSchema
