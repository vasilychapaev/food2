up:
	docker-compose up -d

down:
	docker-compose down

rebuild:
	docker-compose down --volumes --remove-orphans
	docker-compose build --no-cache
	docker-compose up -d

restart:
	docker-compose restart




logs:
	docker-compose logs -f

bash:
	docker-compose exec app bash

tinker:
	docker-compose exec app php artisan tinker

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed
