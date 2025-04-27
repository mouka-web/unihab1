FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
        build-essential \
        default-libmysqlclient-dev \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
