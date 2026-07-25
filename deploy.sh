#!/usr/bin/env bash
# ==============================================================================
# Automated One-Click Deployment Script for Linux / Production Servers
# RTH NEXUS Presensi RFID
# ==============================================================================

set -e

echo -e "\033[36m🚀 Starting Production Deployment RTH NEXUS Presensi RFID...\033[0m"

# 1. Stop existing containers
echo -e "\033[33m🛑 Stopping old containers...\033[0m"
docker-compose down || true

# 2. Start Docker Stack
echo -e "\033[32m🏗️ Starting Docker stack (Live Code Sync active)...\033[0m"
docker-compose up -d

# 3. Wait for MySQL
echo -e "\033[33m⏳ Waiting 15s for MySQL Database to initialize...\033[0m"
sleep 15

# 4. Run Laravel setup commands
echo -e "\033[32m🗄️ Executing database migrations and seeding demo data...\033[0m"
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
docker-compose exec -T app php artisan key:generate --force
docker-compose exec -T app php artisan migrate:fresh --seed --force
docker-compose exec -T app php artisan storage:link --force

echo ""
echo -e "\033[36m==========================================================================\033[0m"
echo -e "\033[32m🎉 DEPLOYMENT SUCCESSFUL!\033[0m"
echo -e "\033[33m🌐 Web Application: http://localhost:8000\033[0m"
echo -e "\033[33m📺 Kiosk Scanner:  http://localhost:8000/kiosk\033[0m"
echo -e "\033[33m🏆 Leaderboard:   http://localhost:8000/leaderboard\033[0m"
echo -e "\033[36m==========================================================================\033[0m"
