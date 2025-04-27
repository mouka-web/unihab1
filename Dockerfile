FROM php:8.2-apache

# Utiliser les nouveaux noms de paquets pour PHP8+
RUN apt-get update \
    && apt-get install -y default-mysql-client default-mysql-server libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
