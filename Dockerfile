FROM dunglas/frankenphp:latest

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les extensions manquantes
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Autoriser Composer en root
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Copier le Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Copier les fichiers composer
COPY composer.json composer.lock ./

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copier tout le projet
COPY . .

# Permissions
RUN mkdir -p var/cache var/log && chmod -R 777 var/

# Compiler les assets et vider le cache
RUN composer dump-autoload --optimize && \
    php bin/console cache:warmup --env=prod || true

EXPOSE 8000