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
echo "[1/7] Pulling latest changes from git..."
git pull origin main

# Install/update PHP dependencies
echo "[2/7] Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install/update Node dependencies & build assets
echo "[3/7] Installing Node dependencies..."
npm install --ignore-scripts

echo "[4/7] Building frontend assets..."
npm run build

# Run database migrations
echo "[5/7] Running migrations..."
php artisan migrate --force

# Clear and rebuild caches
echo "[6/7] Clearing and rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue worker
echo "[7/7] Restarting queue worker..."
php artisan queue:restart

echo ""
echo "========================================="
echo "  Deployment complete!"
echo "========================================="
echo ""
