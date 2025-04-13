# Use the official PHP-Apache image (latest 8.2)
FROM php:8.2-apache

# Install PHP extensions in a single RUN layer to speed up the build
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    fileinfo \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Copy your application code into the web root
COPY . /var/www/html/

# Set proper permissions (optional, good for uploads and security)
RUN chown -R www-data:www-data /var/www/html

# Expose port (Railway automatically detects this)
EXPOSE 80

# Start Apache (this is the default in the base image, but explicit here for Railway)
CMD ["apache2-foreground"]
