param(
    [switch]$SkipMigrations
)

$ErrorActionPreference = "Stop"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Laravel + Vite Application Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Check dependencies
Write-Host "[1/6] Checking required dependencies..." -ForegroundColor Yellow
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Error "Composer is not installed or not in PATH."
    exit 1
}
if (!(Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Error "NPM is not installed or not in PATH. Please install Node.js."
    exit 1
}
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Error "PHP is not installed or not in PATH."
    exit 1
}
Write-Host "Dependencies found." -ForegroundColor Green

# 2. Composer Install
Write-Host "`n[2/6] Installing Composer dependencies..." -ForegroundColor Yellow
composer install

# 3. Environment File and Key
Write-Host "`n[3/6] Setting up environment..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" -Destination ".env"
        Write-Host "Created .env file from .env.example." -ForegroundColor Green
        
        Write-Host "Generating application key..." -ForegroundColor Yellow
        php artisan key:generate
    } else {
        Write-Warning "No .env.example file found. Please create a .env file manually."
    }
} else {
    Write-Host ".env file already exists. Skipping." -ForegroundColor Green
}

# 4. NPM Install
Write-Host "`n[4/6] Installing NPM dependencies..." -ForegroundColor Yellow
npm install

# 5. Build Vite Assets
Write-Host "`n[5/6] Building frontend assets with Vite..." -ForegroundColor Yellow
npm run build

# 6. Database Migrations
Write-Host "`n[6/6] Database setup..." -ForegroundColor Yellow
if ($SkipMigrations) {
    Write-Host "Skipping database migrations as requested." -ForegroundColor Green
} else {
    $response = Read-Host "Do you want to run database migrations now? (y/n)"
    if ($response -match "^[yY]") {
        php artisan migrate
    } else {
        Write-Host "Skipping database migrations." -ForegroundColor Green
    }
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete! " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "`nTo start development, run these commands in separate terminals:"
Write-Host "  1. php artisan serve" -ForegroundColor DarkCyan
Write-Host "  2. npm run dev" -ForegroundColor DarkCyan
Write-Host ""
