#!/bin/bash
# =============================================================================
# Deploy Script — Therapist Lysander Website
# Server: therapistlysander.kodeclouds.com
# Path: /home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com
# =============================================================================

set -e

APP_DIR="/home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com"

echo "========================================="
echo "  Deploying Therapist Lysander Website"
echo "========================================="
echo ""

cd "$APP_DIR"

# Pull latest changes
echo "[1/8] Pulling latest changes from git..."
git pull origin main

# Install/update PHP dependencies
echo "[2/8] Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install/update Node dependencies & build assets
echo "[3/8] Installing Node dependencies..."
npm install --ignore-scripts

echo "[4/8] Building frontend assets..."
npm run build

# Run database migrations
echo "[5/8] Running migrations..."
php artisan migrate --force

# Populate Dutch translations
echo "[6/8] Populating Dutch translations..."
php artisan dutch:populate

# Clear and rebuild caches
echo "[7/8] Clearing and rebuilding caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue worker
echo "[8/8] Restarting queue worker..."
php artisan queue:restart

echo ""
echo "========================================="
echo "  Deployment complete!"
echo "========================================="
echo ""
