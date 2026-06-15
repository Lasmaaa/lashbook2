FROM php:8.3-apache

# 1. Instalējam sistēmas pakotnes, GD un PostgreSQL draiverus
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql gd

# 2. Ieslēdzam Apache mod_rewrite (Laravel maršrutēšanai)
RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Nokopējam projekta failus uz standarta Apache direktoriju
WORKDIR /var/www/html
COPY . .

# 4. Mainām Apache konfigurāciju, lai tā skatās uz "public" mapi
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Piešķiram tiesības mapēm
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

# 6. Palaižam migrācijas un startējam Apache
CMD php artisan migrate --force && apache2-foreground