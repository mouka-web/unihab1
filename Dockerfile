FROM php:8.2-apache

# Installer mysqli et dépendances de compilation
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        build-essential \
        default-libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copier un fichier PHP de test dans le conteneur
COPY index.php /var/www/html/

# Modifier les permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
