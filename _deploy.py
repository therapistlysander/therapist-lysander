import paramiko
import sys
import io

# Fix Windows console encoding for Unicode output
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

import time

HOST = "194.163.151.182"
USER = "root"
PASS = "rakeshmaity"
APP_DIR = "/home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com"
GITHUB_TOKEN = "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"

def run(cmd, timeout=120):
    print(f"  > {cmd[:100]}{'...' if len(cmd) > 100 else ''}")
    stdin, stdout, stderr = client.exec_command(cmd, timeout=timeout)
    out = stdout.read().decode()
    err = stderr.read().decode()
    if out.strip():
        for line in out.strip().split('\n'):
            print(f"    {line}")
    if err.strip():
        for line in err.strip().split('\n'):
            print(f"    [ERR] {line}")
    return stdout.channel.recv_exit_status()

print("Connecting to server...")
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(HOST, username=USER, password=PASS, timeout=30)
print("Connected!\n")

steps = [
    ("[1/9] Git stash + pull", f"cd {APP_DIR} && git stash && git remote set-url origin https://{GITHUB_TOKEN}@github.com/rakeshmaity271/therapistlysander.git && git pull origin main && git stash drop"),
    ("[2/9] Composer install", f"cd {APP_DIR} && COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader --no-dev --no-interaction"),
    ("[3/9] NPM install", f"cd {APP_DIR} && npm install --ignore-scripts"),
    ("[4/9] NPM build", f"cd {APP_DIR} && npm run build"),
    ("[5/9] Migrate", f"cd {APP_DIR} && php artisan migrate --force"),
    ("[6/9] Seed UI translations", f"cd {APP_DIR} && php artisan db:seed --force"),
    ("[7/9] Fix alignment", f"cd {APP_DIR} && php artisan content:fix-alignment"),
    ("[8/9] Cache rebuild", f"cd {APP_DIR} && php artisan view:clear && php artisan config:clear && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache"),
    ("[9/9] Queue restart", f"cd {APP_DIR} && php artisan queue:restart"),
]

for label, cmd in steps:
    print(f"\n{label}")
    rc = run(cmd, timeout=180)
    if rc != 0:
        print(f"  WARNING: {label} returned exit code {rc}")

print("\n\nDeployment complete!")
client.close()
