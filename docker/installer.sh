#!/bin/bash

echo ""
echo "Starting installation"
echo ""

echo ""
echo "=================================================> 0%"
echo ""

echo ""
echo "1) Up the containers"
echo ""

docker-compose -p PRODUCT_MANAGER up -d

echo ""
echo "=================================================> 11.11%"
echo ""

echo ""
echo "2) Creating file .env"
echo ""

docker exec product_manager_application cp .env.example .env

echo ""
echo "=================================================> 22.22%"
echo ""

echo ""
echo "3) Installing dependencies by composer"
echo ""

docker exec product_manager_application composer install

echo ""
echo "=================================================> 33.33%"
echo ""

echo ""
echo "4) Set the application key"
echo ""

docker exec product_manager_application php artisan key:generate

echo ""
echo "=================================================> 44.44%"
echo ""

echo ""
echo "5) Running migrations"
echo ""

docker exec product_manager_application php artisan migrate

echo ""
echo "=================================================> 55.55%"
echo ""

echo ""
echo "6) Running seeders"
echo ""

docker exec product_manager_application php artisan db:seed

echo ""
echo "=================================================> 66.66%"
echo ""

echo ""
echo "7) Running unit tests"
echo ""

docker exec product_manager_application vendor/bin/phpunit tests/Unit/ --testdox

echo ""
echo "=================================================> 77.77%"
echo ""

echo ""
echo "8) Running integration tests"
echo ""

docker exec product_manager_application vendor/bin/phpunit tests/Integration/ --testdox

echo ""
echo "=================================================> 88.88%"
echo ""

echo ""
echo "9) Run the listener"
echo ""

docker exec product_manager_application php artisan queue:listen

echo ""
echo "=================================================> 100%"
echo ""

echo ""
echo "Installation completed"
echo ""
