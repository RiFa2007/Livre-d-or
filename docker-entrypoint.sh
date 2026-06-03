#!/bin/sh
set -e

# Installer les assets JS
php bin/console importmap:install

# Lancer les migrations
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

# Démarrer FrankenPHP
exec frankenphp run --config /etc/frankenphp/Caddyfile