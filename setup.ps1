param(
    [switch]$SkipMigrations
)

$ErrorActionPreference = "Continue" # So we can continue to show what's missing instead of stopping immediately

Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  Welcome to TSHSEMS Setup! (Beginner Friendly Mode)" -ForegroundColor Cyan
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "This script will help you set up the project from scratch.`n"

# 1. Check Dependencies
Write-Host "[1/8] Checking required dependencies..." -ForegroundColor Yellow

$missingDeps = $false

if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "X PHP is missing! " -ForegroundColor Red -NoNewline
    Write-Host "Please install PHP. If you are a beginner, download XAMPP (which includes PHP and MySQL) from: https://www.apachefriends.org/"
    $missingDeps = $true
}

if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "X Composer is missing! " -ForegroundColor Red -NoNewline
    Write-Host "Download and install it from: https://getcomposer.org/download/"
    $missingDeps = $true
}

if (!(Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "X Node.js (NPM) is missing! " -ForegroundColor Red -NoNewline
    Write-Host "Download and install it from: https://nodejs.org/"
    $missingDeps = $true
}

if ($missingDeps) {
    Write-Host "`n[!] Please install the missing tools listed above, restart your computer/terminal, and run this script again." -ForegroundColor Red
    exit 1
}

Write-Host "All basic dependencies (PHP, Composer, NPM) found!" -ForegroundColor Green

# 2. Check Environment and Database
Write-Host "`n[2/8] Checking environment and database..." -ForegroundColor Yellow

if (!(Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" -Destination ".env"
        Write-Host "Created .env file automatically from .env.example." -ForegroundColor Green
    } else {
        Write-Host "Warning: No .env.example found to copy." -ForegroundColor Yellow
    }
}

Write-Host "`nCRITICAL: This project requires a Database (MySQL/MariaDB). " -ForegroundColor Cyan
Write-Host "If you are a beginner and don't know what this means:" -ForegroundColor Cyan
Write-Host "  1. Download and install XAMPP (if you haven't already)." -ForegroundColor Cyan
Write-Host "  2. Open the XAMPP Control Panel." -ForegroundColor Cyan
Write-Host "  3. Click 'Start' next to 'MySQL' and 'Apache'." -ForegroundColor Cyan
Write-Host "  4. Make sure your .env file has DB_CONNECTION=mysql along with root username and empty password." -ForegroundColor Cyan

$dbResponse = Read-Host "`nIs your MySQL database running in XAMPP? (y/n)"
if ($dbResponse -notmatch "^[yY]") {
    Write-Host "Please start 'MySQL' in your XAMPP Control Panel and run this script again." -ForegroundColor Red
    exit 1
}

# We can attempt a simple connection via php artisan db:show to see if db responds
$dbCheck = php artisan db:show 2>&1
if ($dbCheck -match "Connection refused" -or $dbCheck -match "Access denied") {
    Write-Host ""
    Write-Host "X Could not connect to the database or login failed!" -ForegroundColor Red
    Write-Host "Please ensure your MySQL in XAMPP is running and your .env credentials match." -ForegroundColor Red
    $continueAnyway = Read-Host "Do you want to continue anyway? (y/n)"
    if ($continueAnyway -notmatch "^[yY]") {
        exit 1
    }
} else {
    Write-Host "Database connection seems good!" -ForegroundColor Green
}

# 3. Installing Composer dependencies
Write-Host "`n[3/8] Installing PHP dependencies (Backend)..." -ForegroundColor Yellow
composer install

# 4. Generate Application Key
Write-Host "`n[4/8] Checking Application Key..." -ForegroundColor Yellow
$envContent = Get-Content ".env" -Raw
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "Generating application key..." -ForegroundColor Yellow
    php artisan key:generate
    Write-Host "Application key generated successfully in .env." -ForegroundColor Green
} else {
    Write-Host "Application key already exists." -ForegroundColor Green
}

# 5. Installing NPM dependencies
Write-Host "`n[5/8] Installing Node dependencies (Frontend)..." -ForegroundColor Yellow
npm install

# 6. Build Vite assets
Write-Host "`n[6/8] Building Frontend Assets..." -ForegroundColor Yellow
npm run build

# 7. Setup Public Storage
Write-Host "`n[7/8] Setting up Public Storage Link..." -ForegroundColor Yellow
php artisan storage:link

# 8. Database Migrations & Seeding
Write-Host "`n[8/8] Database Setup (Migrations & Seeding)..." -ForegroundColor Yellow
if ($SkipMigrations) {
    Write-Host "Skipping database migrations as requested." -ForegroundColor Green
} else {
    $migResponse = Read-Host "Do you want to reset the database and seed it with dummy data? THIS WILL ERASE EXISTING DATA! (y/n)"
    if ($migResponse -match "^[yY]") {
        Write-Host "Running migrations and seeding. This might take a minute..." -ForegroundColor Yellow
        php artisan migrate:fresh --seed
        Write-Host "Database migrated and seeded successfully!" -ForegroundColor Green
    } else {
        $migResponse2 = Read-Host "Do you want to run new migrations without erasing existing data? (y/n)"
        if ($migResponse2 -match "^[yY]") {
            php artisan migrate
        } else {
            Write-Host "Skipping database setup." -ForegroundColor Green
        }
    }
}

Write-Host "`n================================================================" -ForegroundColor Cyan
Write-Host "  Setup Complete! You are ready to go." -ForegroundColor Green
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "`nTo start the application, you need to open TWO separate terminals in VS Code:"
Write-Host "  Terminal 1 (Backend):" -ForegroundColor DarkCyan
Write-Host "    php artisan serve" -ForegroundColor White
Write-Host "  Terminal 2 (Frontend Auto-reload):" -ForegroundColor DarkCyan
Write-Host "    npm run dev" -ForegroundColor White
Write-Host "`nThen open your web browser and go to: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host ""
