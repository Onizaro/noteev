FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --prefer-dist
RUN composer require firebase/php-jwt

EXPOSE 80
CMD ["apache2-foreground"]
