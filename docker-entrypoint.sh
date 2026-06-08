#!/bin/sh
set -e

# Installer les assets des bundles (EasyAdmin etc.)
php bin/console assets:install --env=prod

# Lancer les migrations
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

# Démarrer FrankenPHP
exec frankenphp run --config /etc/frankenphp/Caddyfile