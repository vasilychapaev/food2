# Food Tracker (Laravel + MongoDB)

## Быстрый старт

```sh
make up         # Запуск docker-compose (если есть)
❌make migrate    # Миграции (если есть)
❌make serve      # Запуск Laravel сервера
```

## Синхронизация данных

- Ручной запуск: `make sync` или `php artisan sync:sheets`
- Cron: автоматически каждый час (см. Kernel.php)
- Webhook: POST `/api/sync` (без авторизации)

## API

- `GET /api/ingredients` — все ингредиенты
- `GET /api/recipes` — все рецепты
- `GET /api/food-log?date=YYYY-MM-DD` — food log за день
- `GET /api/daily-summary?date=YYYY-MM-DD` — дневной отчёт
- `POST /api/sync` — ручная синхронизация (webhook)

## Веб-интерфейс

- `/tracker` — список дней
- `/tracker/{date}` — деталка по дню

## Тесты

```sh
make test
```

## Makefile команды

- `make up` — docker-compose up -d
- `make down` — docker-compose down
- `make serve` — php artisan serve
- `make sync` — php artisan sync:sheets
- `make cron` — php artisan schedule:work
- `make test` — php artisan test
- `make migrate` — php artisan migrate

## Логи

- Все логи пишутся в `storage/logs/laravel.log`
- Логируются все действия sync, ошибки, открытия страниц

## Google Sheets credentials

- Файл `storage/credentials.json` — сервисный ключ Google (скачать из Google Cloud Console, Service Account)

