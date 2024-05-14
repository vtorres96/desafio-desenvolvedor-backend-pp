# desafio-desenvolvedor-backend-pp
application developed with laravel (version 6x)

## How to install

```sh
$ composer install
```

## How to run the dump-autoload

```sh
$ composer dump-autoload
```

## How to connect to database

### create the .env file and set the following variables

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pp
DB_USERNAME=homestead
DB_PASSWORD=secret
```

## How to run the migrations

```sh
$ php artisan migrate
```

## How to run the seeders

```sh
$ php artisan db:seed
```

## How to create a key

```sh
$ php artisan key:generate
```

## How to start the application

```sh
$ php artisan serve
```

### Listening port on 8000

```sh
$ http://localhost:8000/
```
