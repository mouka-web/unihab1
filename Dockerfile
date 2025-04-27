FROM php:8.2-apache

# Mise à jour des paquets + installation de la bonne version du client MySQL
RUN apt-get update \
    && apt-get install -y default-libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copier ton projet PHP dans le conteneur
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80
