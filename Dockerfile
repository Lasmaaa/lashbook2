FROM php:8.2-apache

# Instalējam nepieciešamos paplašinājumus un Composer
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nokopējam projekta failus
WORKDIR /var/web
COPY . .

# Uzstādām pareizo Apache mapi un tiesības
RUN sed -ri -e 's!/var/www/html!/var/web/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/web/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN composer install --no-dev --optimize-autoloader

# Atveram portu, ko prasa Render
EXPOSE 80