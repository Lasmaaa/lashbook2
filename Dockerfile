FROM php:8.3-apache

# 1. Instalējam sistēmas pakotnes, GD un PostgreSQL draiverus
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev libpq-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql gd

# 2. Instalējam Node.js un NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y openssl nodejs

# 3. Ieslēdzam Apache mod_rewrite
RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Nokopējam projekta failus
WORKDIR /var/www/html
COPY . .

# 5. Uzstādām PHP pakotnes, pilnībā ignorējot skriptus būvēšanas laikā
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6. Uzstādām Node pakotnes un uzbūvējam stilus
RUN npm install && npm run build

# 7. Mainām Apache konfigurāciju uz public mapi
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 8. Piešķiram tiesības mapēm
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 9. Kad konteiners startējas, palaidīs pakotņu atklāšanu, migrācijas un serveri
CMD php artisan package:discover --ansi && php artisan migrate --force && apache2-foreground