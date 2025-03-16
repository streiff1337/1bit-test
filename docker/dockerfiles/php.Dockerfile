FROM php:8.3-fpm


RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    unzip \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd xml zip mysqli pdo_mysql mbstring

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
