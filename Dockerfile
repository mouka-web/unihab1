# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer l'extension mysqli (pour se connecter à ta base de données)
RUN docker-php-ext-install mysqli

# Copier tous les fichiers de ton projet dans le dossier du serveur Apache
COPY . /var/www/html/

# (Optionnel) Donner les bons droits sur les fichiers
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour accéder à ton site
EXPOSE 80
