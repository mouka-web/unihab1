FROM php:8.2-apache

# Installer les dépendances et mysqli
RUN apt-get update \
    && apt-get install -y \
        mariadb-server \
        libmysqlclient-dev \
        && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copier les fichiers de ton projet dans le conteneur
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour accéder à ton site
EXPOSE 80

# Démarrer MariaDB et Apache
CMD service mariadb start && apache2-foreground
