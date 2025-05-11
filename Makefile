up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

build:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

rebuild:
	docker-compose down --remove-orphans --volumes
	docker-compose build --no-cache
	docker-compose up -d

bash:
	docker-compose exec app bash

test:
	docker-compose exec app php artisan test

status:
	docker-compose ps

art:
	docker-compose exec app php artisan $(cmd)





logs:
	docker-compose logs -f

tinker:
	docker-compose exec app php artisan tinker

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

serve:
	docker-compose exec app php artisan serve --host=0.0.0.0

sync:
	docker-compose exec app php artisan sync:sheets

cron:
	docker-compose exec app php artisan schedule:work

