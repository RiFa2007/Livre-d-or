FROM dunglas/frankenphp:latest

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod

WORKDIR /app

COPY Caddyfile.txt /etc/caddy/Caddyfile
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN mkdir -p var/cache var/log && chmod -R 777 var/

RUN composer dump-autoload --optimize && \
    php bin/console cache:warmup --env=prod || true

EXPOSE 80