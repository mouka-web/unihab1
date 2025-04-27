# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires et l'extension mysqli
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        mariadb-server \
        libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copier le script d'initialisation dans le conteneur et le rendre exécutable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copier tout le code de l'application dans le répertoire web d'Apache
COPY . /var/www/html/

# Ajuster les permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port HTTP
EXPOSE 80

# Utiliser le script d'entrypoint personnalisé
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
