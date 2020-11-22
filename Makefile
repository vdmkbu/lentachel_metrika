docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up -d --build

init: generate composer-install migration

composer-install:
	docker-compose exec php-cli composer install

generate:
	docker-compose exec php-cli php artisan key:generate

migration:
	docker-compose exec php-cli php artisan migrate
