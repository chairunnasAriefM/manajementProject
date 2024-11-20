<div style="text-align: center;">
  <img src="https://media.giphy.com/media/XUqcmSSeTUbupSeGA4/giphy.gif" alt="banner" style="width: 100%; max-width: 800px;">
</div>

## Getting Started

1. Install the dependencies

```shell
composer install
```

2. Copy `.env.example` to `.env`

```shell
cp .env.example .env
```

or di cmd

```shell
copy .env.example .env
```

3. Generate application key

```shell
php artisan key:generate
```

5. Start the webserver

```shell
php artisan serve
```

## Buat Database

1. Run Migrations

```shell
php artisan migrate
```

2. Run Database Seeders

```shell
php artisan db:seed
```

## ini default adminnya di seeder

```json
{
    "name": "admin",
    "email": "admin@gmail.com",
    "password": "123"
}
```
