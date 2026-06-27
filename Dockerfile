FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip git libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    libicu-dev curl openssl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql gd mbstring exif bcmath intl \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_PROCESS_TIMEOUT=0

COPY composer.json composer.lock ./
RUN set -e; \
    for attempt in 1 2 3 4 5; do \
      if composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist; then \
        exit 0; \
      fi; \
      echo "composer install failed (attempt ${attempt}/5), retrying in 15s..."; \
      sleep 15; \
    done; \
    exit 1

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache

RUN cp .env.example .env \
    && APP_KEY="base64:$(openssl rand -base64 32 | tr -d '\n')" \
    && export APP_KEY \
    && php docker/sync-env.php

RUN composer dump-autoload --optimize --no-interaction \
    && npm run build \
    && rm -rf node_modules

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && cp docker/laravel-env.conf /etc/apache2/conf-enabled/laravel-env.conf

RUN mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x docker/start.sh

EXPOSE 8080

CMD ["docker/start.sh"]
