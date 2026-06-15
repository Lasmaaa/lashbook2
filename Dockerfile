FROM php:8.3-apache

# Instalējam nepieciešamās sistēmas pakotnes, GD un PostgreSQL draiverus
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nokopējam projekta failus
WORKDIR /var/web
COPY . .

# Uzstādām pareizo Apache mapi un tiesības
RUN sed -ri -e 's!/var/www/html!/var/web/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/web/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Uzstādām Laravel tiesības mapēm
RUN chown -R www-data:www-data /var/web/storage /var/web/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

# Automātiski palaižam datubāzes migrācijas un pēc tam startējam Apache serveri
CMD php artisan migrate --force && apache2-foreground