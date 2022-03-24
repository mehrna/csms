FROM php:8.1-alpine

WORKDIR /var/www

COPY . /var/www

RUN docker-php-ext-install pdo pdo_mysql

RUN chown -R www-data:www-data /var/www

# Time Zone
RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini

EXPOSE 8000

CMD [ "php", "-S", "0.0.0.0:8000", "-t", "public" ]