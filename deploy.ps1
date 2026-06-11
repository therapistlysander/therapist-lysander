# =============================================================================
# Deploy Script — Therapist Lysander Website (Windows PowerShell)
# Server: 194.163.151.182 | User: root
# Remote path: /home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com
# =============================================================================
# Usage:  .\deploy.ps1
# You will be prompted for the SSH password once.
# =============================================================================

$SSH_HOST = "root@194.163.151.182"
$APP_DIR  = "/home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com"

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  Deploying Therapist Lysander Website"    -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Build the remote command — all deploy steps in one SSH session
$REMOTE_CMD = @"
set -e
cd $APP_DIR

echo '[1/8] Pulling latest changes from git...'
git stash && git pull origin main && git stash drop

echo '[2/8] Installing Composer dependencies...'
COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader --no-dev --no-interaction

echo '[3/8] Installing Node dependencies...'
npm install --ignore-scripts

echo '[4/8] Building frontend assets...'
npm run build

echo '[5/8] Running migrations...'
php artisan migrate --force

echo '[6/8] Fixing rich-text alignment...'
php artisan content:fix-alignment

echo '[7/8] Clearing and rebuilding caches...'
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo '[8/8] Restarting queue worker...'
php artisan queue:restart

echo ''
echo '========================================='
echo '  Deployment complete!'
echo '========================================='
"@

Write-Host "Connecting to $SSH_HOST ..." -ForegroundColor Yellow
Write-Host "Enter SSH password when prompted." -ForegroundColor Yellow
Write-Host ""

# Execute SSH — password prompt is handled by the terminal
ssh -t $SSH_HOST "bash -c '$REMOTE_CMD'"

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "Deploy finished successfully." -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "Deploy failed (exit code $LASTEXITCODE)." -ForegroundColor Red
}
