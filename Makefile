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

serve:
	docker-compose exec app php artisan serve --host=0.0.0.0

sync:
	docker-compose exec app php artisan sync:sheets

cron:
	docker-compose exec app php artisan schedule:work

test:
	docker-compose exec app php artisan test

status:
	docker-compose ps

art:
	docker-compose exec app php artisan $(cmd)
