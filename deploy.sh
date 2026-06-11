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
echo "[1/9] Pulling latest changes from git..."
git pull origin main

# Install/update PHP dependencies
echo "[2/9] Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install/update Node dependencies & build assets
echo "[3/9] Installing Node dependencies..."
npm install --ignore-scripts

echo "[4/9] Building frontend assets..."
npm run build

# Run database migrations
echo "[5/9] Running migrations..."
php artisan migrate --force

# Populate Dutch translations
echo "[6/9] Populating Dutch translations..."
php artisan dutch:populate

# Fix rich-text alignment in existing content
echo "[7/9] Fixing rich-text alignment..."
php artisan content:fix-alignment

# Clear and rebuild caches
echo "[8/9] Clearing and rebuilding caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue worker
echo "[9/9] Restarting queue worker..."
php artisan queue:restart

echo ""
echo "========================================="
echo "  Deployment complete!"
echo "========================================="
echo ""
