FROM php:8.2-apache

# Installer mysqli et un serveur MySQL via le méta-paquet default-mysql-server
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y \
      libmysqlclient-dev \
      default-mysql-server \
 && docker-php-ext-install mysqli \
 && rm -rf /var/lib/apt/lists/*

# Copier l’application
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Démarrer MySQL puis Apache
CMD service mysql start && apache2-foreground
