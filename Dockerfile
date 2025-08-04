FROM php:8.2-apache

# Enable mod_rewrite for .htaccess to work
RUN a2enmod rewrite \
    && docker-php-ext-install pdo_mysql mysqli

WORKDIR /var/www/html
COPY . /var/www/html

# Set Apache to use .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
