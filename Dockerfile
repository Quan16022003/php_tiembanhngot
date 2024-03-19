FROM php:8.0-apache

# Set working directory
WORKDIR /var/www/html

COPY . .

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
