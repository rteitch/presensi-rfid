# ==============================================================================
# Automated One-Click Deployment Script for Windows (Docker Stack)
# RTH NEXUS Presensi RFID
# ==============================================================================

Write-Host "Starting Deployment RTH NEXUS Presensi RFID (Docker Stack)..." -ForegroundColor Cyan

# Check Docker installation
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Docker is not installed or not in PATH! Please install Docker Desktop first." -ForegroundColor Red
    Exit 1
}

# 1. Stop existing containers
Write-Host "Stopping old containers if running..." -ForegroundColor Yellow
docker-compose down

# 2. Start Docker Stack (Live Volume Synced)
Write-Host "Starting Docker containers (Live Code Sync active)..." -ForegroundColor Green
docker-compose up -d

# 3. Wait for MySQL to be ready
Write-Host "Waiting 15 seconds for MySQL Database initialization..." -ForegroundColor Yellow
Start-Sleep -Seconds 15

# 4. Run Laravel Migrations & Seeders
Write-Host "Executing database migrations and seeding demo data..." -ForegroundColor Green
Remove-Item bootstrap/cache/packages.php, bootstrap/cache/services.php -Force -ErrorAction SilentlyContinue
docker-compose exec -T app php artisan key:generate --force
docker-compose exec -T app php artisan migrate:fresh --seed --force
docker-compose exec -T app php artisan storage:link --force

# 5. Execute Automated Test Suite Verification (101 Tests)
Write-Host "Running automated test suite verification (101 tests)..." -ForegroundColor Green
docker-compose exec -T -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test

Write-Host ""
Write-Host "==========================================================================" -ForegroundColor Cyan
Write-Host "DEPLOYMENT SUCCESSFUL!" -ForegroundColor Green
Write-Host "Web Dashboard: http://localhost:8000" -ForegroundColor Yellow
Write-Host "Kiosk Scanner:  http://localhost:8000/kiosk" -ForegroundColor Yellow
Write-Host "Leaderboard:   http://localhost:8000/leaderboard" -ForegroundColor Yellow
Write-Host "Admin Email:   admin@sekolah.test" -ForegroundColor White
Write-Host "Admin Pass:    password" -ForegroundColor White
Write-Host "==========================================================================" -ForegroundColor Cyan
