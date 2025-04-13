FROM php:8.2-apache

# Copy your app into the container
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional but common)
RUN a2enmod rewrite