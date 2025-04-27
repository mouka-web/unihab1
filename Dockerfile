# Utiliser une image officielle PHP avec Apache
FROM php:8.2-apache

# Copier ton code dans le dossier de travail du serveur Apache
COPY . /var/www/html/

# (Optionnel) Activer les modules PHP si besoin
# RUN docker-php-ext-install mysqli pdo pdo_mysql

# Exposer le port 80 (port web)
EXPOSE 80
