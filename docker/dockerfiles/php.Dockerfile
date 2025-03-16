FROM php:8.2-fpm

ENV USER_ID=1000
ENV GROUP_ID=1000

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

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

WORKDIR /var/www/html

ENV LANG ru_RU.UTF-8
ENV LC_ALL ru_RU.UTF-8

USER "${USER_ID}:${GROUP_ID}"

RUN mkdir -p /tmp/php_sessions/www \
    && chown -R ${USER_ID}:${GROUP_ID} /tmp/php_sessions/www \
    && chmod 733 /tmp/php_sessions/www

RUN mkdir -p /tmp/php_upload/www \
    && chown -R ${USER_ID}:${GROUP_ID} /tmp/php_upload/www \
    && chmod 733 /tmp/php_upload/www

EXPOSE 9000

CMD ["php-fpm"]
