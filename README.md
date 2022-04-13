<h1 align="center">Product Manager</h1>

Leroy Merlin PHP Backend Technical Challenge for study purposes.

##

* [Setup](#setup)
* [Endpoints](#endpoints)
* [Technologies Used](#technologies-used)

## Setup

#### Prerequisites

Before starting, make sure you have Docker installed on your machine.

```bash
# Clone this repository
$ git clone git@github.com:rafaelzorn/product-manager.git

# Access the docker folder that is at the root of the project
$ cd docker

# Run the script installer.sh
$ ./installer.sh
```

When running the ``installer.sh`` script it will execute the following commands:

- docker-compose -p PRODUCT_MANAGER up -d
- docker exec product_manager_application cp .env.example .env
- docker exec product_manager_application composer install
- docker exec product_manager_application php artisan key:generate
- docker exec product_manager_application php artisan migrate
- docker exec product_manager_application php artisan db:seed
- docker exec product_manager_application vendor/bin/phpunit tests/Unit/ --testdox
- docker exec product_manager_application vendor/bin/phpunit tests/Integration/ --testdox
- docker exec product_manager_application php artisan queue:listen

## Endpoints

#### Products

```
    GET localhost:8000/api/v1/products
```
```
    POST localhost:8000/api/v1/products
    
    # Exemplo Payload
    {
	    "spreadsheet": products.xlsx,
    }
```
```
    GET localhost:8000/api/v1/products/1001
```
```
    PUT localhost:8000/api/v1/products/1001
    
    # Exemplo Payload
    {
	    "category_id": 123123,
	    "name": "Furadeira X",
	    "free_shipping": 0,
	    "description": "Furadeira eficiente X",
	    "price": 100.00
    }
```
```
    DELETE localhost:8000/api/v1/products/1001
```

#### Processed Files

```
    GET localhost:8000/api/v1/processed-files/414
```

## Technologies Used

- [Laravel 9.2](https://laravel.com/)
- [RabbitMQ](https://www.rabbitmq.com/)
- [RabbitMQ Queue driver for Laravel](https://github.com/vyuldashev/laravel-queue-rabbitmq)
- [Laravel Excel](https://laravel-excel.com/)
