FROM php:7.4-apache

#Add Laravel necessary php extensions
RUN apt-get --allow-releaseinfo-change update
RUN apt-get install -f -y \
    openssl\
    zip\
    unzip \
    git\
    vim \
    default-mysql-client \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libwebp-dev \
    libicu-dev \
    && docker-php-ext-install -j$(nproc) zip mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install exif pcntl intl \
    && docker-php-ext-enable intl

# Create working directory
RUN mkdir -p /var/www/acer

# Add current directory contents to /var/www/opms
COPY . /var/www/acer
WORKDIR /var/www/acer

ENV PATH /var/www/acer/vendor/bin:$PATH
ENV CFLAGS -DBIG_SECURITY_HOLE:$CFLAGS


RUN mkdir -p bootstrap/cache

RUN chgrp -R www-data storage bootstrap/cache
RUN chown -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache

ENV APP_NAME "Acer Test"

RUN sed -ri -e 's!/var/www/html!/var/www/acer/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/acer/public/!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#Restart HTTPD
RUN a2enmod rewrite && service apache2 restart
