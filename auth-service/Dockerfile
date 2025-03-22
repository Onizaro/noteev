FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY . .

EXPOSE 80
CMD ["apache2-foreground"]
