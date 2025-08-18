FROM php:8.2-apache

# Install dependencies for Composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable mod_rewrite for .htaccess to work
RUN a2enmod rewrite \
    && docker-php-ext-install pdo_mysql mysqli

WORKDIR /var/www/html

# Copy only composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

COPY . /var/www/html

# Set Apache to use .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
