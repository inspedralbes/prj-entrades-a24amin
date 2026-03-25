FROM php:8.4-cli

# Instal·lem dependències del sistema i l'extensió PDO + ZIP (necessària per composer)
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instal·lem Composer directament des de la imatge oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Quan executi el contenidor llançarà aquest comandament
CMD php artisan serve --host=0.0.0.0 --port=8000
