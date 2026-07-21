FROM php:8.3-apache

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

# Custom PHP config - hide errors in production
RUN { \
        echo 'display_errors = Off'; \
        echo 'log_errors = On'; \
        echo 'error_log = /var/log/php_errors.log'; \
        echo 'date.timezone = Asia/Vientiane'; \
        echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT'; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html
COPY . /var/www/html/

# Remove dev/installer files not needed for production
RUN rm -f composer-setup.php composer.json composer.lock

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
