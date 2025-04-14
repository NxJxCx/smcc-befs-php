FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip fileinfo

# Configure PHP
COPY ./php.ini /usr/local/etc/php/

# Copy your app code
COPY ./app /var/www/html

# Copy the filemanager files
COPY ./filemanager /var/www/html/filemanager

# Configure NGINX
COPY ./nginx.conf /etc/nginx/nginx.conf

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 80

# Start services
CMD service php8.2-fpm start && nginx -g 'daemon off;'
