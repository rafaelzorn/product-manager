<h1 align="center">Product Manager</h1>

Simple project to test Laravel 9.2, the project consists of managing a register of products through an API.

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

##### Payload Examples

## Technologies Used

- [Laravel 9.2](https://laravel.com/)
- [RabbitMQ](https://www.rabbitmq.com/)
- [RabbitMQ Queue driver for Laravel](https://github.com/vyuldashev/laravel-queue-rabbitmq)
