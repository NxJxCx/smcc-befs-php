FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpq-dev \
    libicu-dev \
    supervisor \
    && docker-php-ext-install mysqli pdo pdo_mysql zip fileinfo \
    && apt-get clean

RUN apt-get install -y supervisor

# Configure PHP
COPY ./php.ini /usr/local/etc/php/

# Copy your app code
COPY ./app /var/www/html

# Copy the filemanager files
COPY ./filemanager/index.php /var/www/html/filemanager.php

# Configure NGINX
COPY ./nginx.conf /etc/nginx/nginx.conf

# Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port
EXPOSE 80

CMD ["/usr/bin/supervisord"]