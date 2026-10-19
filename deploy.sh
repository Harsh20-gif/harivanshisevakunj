#!/usr/bin/env bash
# ==============================================================================
# Harivanshi Seva Kunj — Automated Production Deployment Script
# ==============================================================================
set -e

echo "🚀 [1/7] Putting application into maintenance mode..."
php artisan down || true

echo "📦 [2/7] Installing production composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "🗄️ [3/7] Running database migrations & seeding initial catalogue..."
php artisan migrate --force
php artisan db:seed --class=CatalogueSeeder --force || true

echo "🔗 [4/7] Verifying storage symbolic link..."
php artisan storage:link || true

echo "⚡ [5/7] Building frontend assets (Vite)..."
if command -v npm &> /dev/null; then
    npm ci --prefer-offline --no-audit || npm install
    npm run build
fi

echo "🧹 [6/7] Optimizing caches for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "✨ [7/7] Bringing application back online..."
php artisan up

echo "✅ Deployment completed successfully! Harivanshi Seva Kunj is live."
