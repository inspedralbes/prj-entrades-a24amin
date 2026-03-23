FROM php:8.3-cli

# Instal·lem l'extensió PDO per poder connectar-nos amb el MySQL
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

# Quan executi el contenidor llençarà aquest comandament automàticament
CMD php artisan serve --host=0.0.0.0 --port=8000
