FROM php:8.2-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libicu-dev \
    libsqlite3-dev \
    libonig5 \
    libcurl4 \
    curl \
    libssl-dev \
    libaio-dev \
    git \
    gettext-base \
    && docker-php-ext-install pdo pdo_mysql mysqli fileinfo

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Allow Apache to use dynamic PORT from Railway
ENV PORT=8080

# Replace Apache port with the one set by Railway at runtime
RUN echo "Listen ${PORT}" > /etc/apache2/ports.conf

# Setup custom virtual host with dynamic port
COPY apache-template.conf /etc/apache2/sites-available/000-default.conf.template
RUN envsubst < /etc/apache2/sites-available/000-default.conf.template > /etc/apache2/sites-available/000-default.conf


RUN echo "<?php phpinfo(); ?>" > /var/www/html/index.php

# Copy your application code into the web root
COPY . /var/www/html/befs

# Set proper permissions (optional, good for uploads and security)
RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/html/befs

# Expose the default port (will be replaced by Railway with dynamic mapping)
EXPOSE 9000

# Start Apache in foreground
CMD ["apache2-foreground"]