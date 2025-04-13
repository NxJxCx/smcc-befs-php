# Use the official PHP-Apache image
FROM php:8.2-apache

# Enable Apache rewrite module for .htaccess
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli fileinfo

# Copy your app code into the container
COPY . /var/www/html/

# Set proper permissions (optional but useful for uploads)
RUN chown -R www-data:www-data /var/www/html

# Expose port (Railway detects port automatically, so not required explicitly)
EXPOSE 80