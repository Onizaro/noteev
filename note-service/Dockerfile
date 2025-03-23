FROM php:8.1-apache

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier tout le projet
COPY . /var/www/html/

# Installer les dépendances PHP
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Copier la config Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
