FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    bash \
    # libpng-dev \
    # libonig-dev \
    # libxml2-dev \
    # libfreetype6-dev \
    # libjpeg62-turbo-dev \
    # libmcrypt-dev \
    # libpq-dev \
    # libicu-dev \
    supervisor
RUN docker-php-ext-install mysqli pdo pdo_mysql fileinfo

# RUN apt-get install -y supervisor

# Configure PHP
COPY ./php.ini /usr/local/etc/php/

# Copy your app code
COPY ./app /var/www/html

# Configure NGINX
COPY ./nginx.conf /etc/nginx/nginx.conf

# Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]