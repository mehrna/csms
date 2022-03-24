FROM composer:latest

WORKDIR /var/www

# Installed PDO_MYSQL to use Phinx migration tool
RUN docker-php-ext-install pdo pdo_mysql

ENTRYPOINT [ "composer" ]