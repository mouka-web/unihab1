# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Mettre à jour le système et installer les paquets nécessaires (MySQL et mysqli)
RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    mysql-server \
    && docker-php-ext-install mysqli

# Copier tous les fichiers de ton projet dans le dossier Apache (/var/www/html)
COPY . /var/www/html/

# Donner les bons droits sur les fichiers
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour accéder à ton site
EXPOSE 80

# Démarrer MySQL et Apache au lancement du conteneur
CMD service mysql start && apache2-foreground
