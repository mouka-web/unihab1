FROM php:8.2-apache

# Installer les dépendances nécessaires pour mysqli
RUN apt-get update && apt-get install -y \
    default-libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copier les fichiers PHP
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
