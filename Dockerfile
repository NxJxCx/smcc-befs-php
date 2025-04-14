FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    openssl \
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
    ca-certificates \
    && docker-php-ext-install mysqli pdo pdo_mysql zip fileinfo \
    && apt-get clean

# Copy the self-signed certificate to the container
COPY ./cert/cert.pem /usr/local/share/ca-certificates/my-cert.crt

COPY ./cert/cert.pem /etc/ssl/certs/my-cert.pem
COPY ./cert/key.pem /etc/ssl/private/my-cert.pem

# Update the certificate store
RUN update-ca-certificates

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

# Expose port
EXPOSE 443 80

CMD ["/usr/bin/supervisord"]