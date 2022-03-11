FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip \
    && docker-php-ext-install -j$(nproc) gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets \
    && docker-php-ext-install -j$(nproc) pdo_mysql

RUN chmod -R 755 /var/www/html && chown -R www-data:www-data /var/www/html
