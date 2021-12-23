FROM php:8.0-fpm
RUN apt-get update && apt-get install -y \
libmcrypt-dev \
libxml2-dev \
libzip-dev \
unzip wget gnupg ntp
RUN docker-php-ext-install zip
RUN docker-php-ext-install opcache
RUN chown -R www-data:www-data /var/www
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
# Load composer packages
COPY ./composer.json /var/www/html/composer.json
# RUN composer install
