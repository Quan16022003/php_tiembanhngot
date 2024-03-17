FROM php:8.0-apache

# Set working directory
WORKDIR /var/www/html

COPY . .

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
