FROM dunglas/frankenphp:latest

WORKDIR /app

# Copier le Caddyfile en premier
COPY Caddyfile.txt /etc/caddy/Caddyfile

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