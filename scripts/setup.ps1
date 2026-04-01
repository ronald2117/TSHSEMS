#requires -Version 5.1

<#
TSHSEMS setup helper (Windows PowerShell)

This script:
- Validates prerequisites (php, composer, node/npm)
- Creates .env from .env.example (if missing)
- Optionally switches DB config to sqlite and creates database file
- Installs PHP/JS dependencies
- Generates APP_KEY
- Runs migrations (optionally fresh) and seeders
- Builds frontend assets
- Optionally starts dev servers

Examples:
    powershell -ExecutionPolicy Bypass -File scripts\setup.ps1
    powershell -ExecutionPolicy Bypass -File scripts\setup.ps1 -Database mysql
    powershell -ExecutionPolicy Bypass -File scripts\setup.ps1 -SkipServe
    powershell -ExecutionPolicy Bypass -File scripts\setup.ps1 -DryRun
#>

[CmdletBinding()]
param(
    [ValidateSet('sqlite', 'mysql')]
    [string]$Database = 'sqlite',

    # MySQL settings (used when -Database mysql)
    [string]$MySqlHost = '127.0.0.1',
    [int]$MySqlPort = 3306,
    [string]$MySqlDatabase = 'tshsems_db',
    [string]$MySqlUsername = 'root',
    [string]$MySqlPassword = '',

    # When set, overwrites DB_* values in an existing .env as well (not just newly-created ones)
    [switch]$ForceDbConfig,

    # Skip MySQL server reachability checks
    [switch]$SkipMySqlChecks,

    [switch]$Fresh,
    [switch]$Seed,

    [switch]$SkipComposer,
    [switch]$SkipNpm,
    [switch]$SkipMigrate,
    [switch]$SkipBuild,
    [switch]$SkipServe,

    [int]$Port = 8000,

    [switch]$DryRun
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$script:EnvCreated = $false

function Write-Section([string]$Title) {
    Write-Host "" 
    Write-Host "== $Title ==" -ForegroundColor Cyan
}

function Write-Warn([string]$Message) {
    Write-Host "WARNING: $Message" -ForegroundColor Yellow
}

function Write-Ok([string]$Message) {
    Write-Host "OK: $Message" -ForegroundColor Green
}

function Assert-FileExists([string]$Path, [string]$Hint) {
    if (-not (Test-Path -LiteralPath $Path)) {
        throw "Missing required file '$Path'. $Hint"
    }
}

function Get-CommandPath([string]$Name) {
    $cmd = Get-Command $Name -ErrorAction SilentlyContinue
    if ($null -eq $cmd) { return $null }
    return $cmd.Source
}

function Assert-CommandExists([string]$Name, [string]$InstallHint) {
    $path = Get-CommandPath $Name
    if ([string]::IsNullOrWhiteSpace($path)) {
        throw "Required command '$Name' was not found in PATH. $InstallHint"
    }
    Write-Ok "$Name found: $path"
}

function Invoke-Step([string]$Label, [string]$Command, [string[]]$Arguments) {
    $argText = ''
    if ($Arguments -and $Arguments.Count -gt 0) {
        $argText = ($Arguments | ForEach-Object { if ($_ -match '\s') { '"' + $_ + '"' } else { $_ } }) -join ' '
    }

    Write-Host "-> $Label" -ForegroundColor White
    Write-Host "   $Command $argText" -ForegroundColor DarkGray

    if ($DryRun) { return }

    $global:LASTEXITCODE = 0
    & $Command @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Step failed ($Label). Exit code: $LASTEXITCODE"
    }
}

function Ensure-EnvFile() {
    Assert-FileExists ".env.example" "Make sure you're running this from the repo root."

    if (-not (Test-Path -LiteralPath ".env")) {
        if ($DryRun) {
            Write-Host "(dry-run) Would create .env from .env.example" -ForegroundColor DarkGray
        } else {
            Copy-Item -LiteralPath ".env.example" -Destination ".env" -Force
            Write-Ok "Created .env from .env.example"
            $script:EnvCreated = $true
        }
    } else {
        Write-Ok ".env already exists"
        $script:EnvCreated = $false
    }
}

function Check-MySqlServer() {
    Write-Section "MySQL Preflight"

    $mysqlClient = Get-CommandPath "mysql"
    if ([string]::IsNullOrWhiteSpace($mysqlClient)) {
        Write-Warn "MySQL client (mysql.exe) not found in PATH. This is OK if you only need PHP to connect, but it usually means MySQL isn't installed." 
    } else {
        Write-Ok "mysql client found: $mysqlClient"
    }

    $serviceCandidates = @()
    try {
        $serviceCandidates += Get-Service -Name "mysql" -ErrorAction SilentlyContinue
        $serviceCandidates += Get-Service -Name "mysqld" -ErrorAction SilentlyContinue
        $serviceCandidates += Get-Service -Name "MySQL*" -ErrorAction SilentlyContinue
        $serviceCandidates += Get-Service -Name "MariaDB*" -ErrorAction SilentlyContinue
    } catch {
        # ignore
    }

    $serviceCandidates = @(
        $serviceCandidates |
            Where-Object { $null -ne $_ } |
            Sort-Object -Property Name -Unique
    )

    if ($serviceCandidates.Count -eq 0) {
        Write-Warn "No Windows service named 'MySQL*' or 'MariaDB*' was found. If you're using XAMPP/WAMP/Docker/remote MySQL, this may be expected." 
    } else {
        foreach ($svc in $serviceCandidates) {
            $state = $svc.Status
            if ($state -eq 'Running') {
                Write-Ok "Service running: $($svc.Name)"
            } else {
                Write-Warn "Service not running: $($svc.Name) (status: $state)"
            }
        }
    }

    if ($DryRun) {
        Write-Host "(dry-run) Would test TCP connectivity to ${MySqlHost}:${MySqlPort}" -ForegroundColor DarkGray
        return
    }

    $reachable = $false
    try {
        $reachable = Test-NetConnection -ComputerName $MySqlHost -Port $MySqlPort -InformationLevel Quiet
    } catch {
        $reachable = $false
    }

    if ($reachable) {
        Write-Ok "MySQL port reachable: ${MySqlHost}:${MySqlPort}"
    } else {
        Write-Warn "Cannot reach MySQL on ${MySqlHost}:${MySqlPort}. Start your MySQL server (or update -MySqlHost/-MySqlPort), then re-run the script."
    }
}

function Set-EnvValue([string]$Key, [string]$Value) {
    $envPath = ".env"
    if ($DryRun) {
        Write-Host "(dry-run) Would set $Key=$Value in .env" -ForegroundColor DarkGray
        return
    }

    Assert-FileExists $envPath "Run Ensure-EnvFile first."

    $content = Get-Content -LiteralPath $envPath -Raw

    $escapedKey = [Regex]::Escape($Key)
    $pattern = "(?m)^$escapedKey=.*$"
    $replacement = "$Key=$Value"

    if ($content -match $pattern) {
        $content = [Regex]::Replace($content, $pattern, $replacement)
    } else {
        if (-not ($content.EndsWith("`n"))) { $content += "`n" }
        $content += "$replacement`n"
    }

    Set-Content -LiteralPath $envPath -Value $content -NoNewline
}

function Configure-Database([string]$Db) {
    if ($Db -eq 'sqlite') {
        Write-Section "Database: SQLite"

        if (-not (Test-Path -LiteralPath "database")) {
            if ($DryRun) {
                Write-Host "(dry-run) Would create directory: database" -ForegroundColor DarkGray
            } else {
                New-Item -ItemType Directory -Path "database" | Out-Null
            }
        }

        $sqlitePath = Join-Path -Path (Get-Location) -ChildPath "database\database.sqlite"
        if (-not (Test-Path -LiteralPath $sqlitePath)) {
            if ($DryRun) {
                Write-Host "(dry-run) Would create SQLite database file: $sqlitePath" -ForegroundColor DarkGray
            } else {
                New-Item -ItemType File -Path $sqlitePath | Out-Null
                Write-Ok "Created SQLite database file: $sqlitePath"
            }
        } else {
            Write-Ok "SQLite database file exists: $sqlitePath"
        }

        # Laravel accepts absolute path for sqlite
        Set-EnvValue -Key "DB_CONNECTION" -Value "sqlite"
        Set-EnvValue -Key "DB_DATABASE" -Value "$sqlitePath"
        Set-EnvValue -Key "DB_HOST" -Value ""
        Set-EnvValue -Key "DB_PORT" -Value ""
        Set-EnvValue -Key "DB_USERNAME" -Value ""
        Set-EnvValue -Key "DB_PASSWORD" -Value ""

        Write-Ok "Configured .env for sqlite"
        return
    }

    if ($Db -eq 'mysql') {
        Write-Section "Database: MySQL"

        $shouldWriteAll = $ForceDbConfig -or $script:EnvCreated
        Set-EnvValue -Key "DB_CONNECTION" -Value "mysql"

        if ($shouldWriteAll) {
            Set-EnvValue -Key "DB_HOST" -Value "$MySqlHost"
            Set-EnvValue -Key "DB_PORT" -Value "$MySqlPort"
            Set-EnvValue -Key "DB_DATABASE" -Value "$MySqlDatabase"
            Set-EnvValue -Key "DB_USERNAME" -Value "$MySqlUsername"
            Set-EnvValue -Key "DB_PASSWORD" -Value "$MySqlPassword"
            Write-Ok "Configured DB_* values in .env for mysql"
        } else {
            Write-Ok "Set DB_CONNECTION=mysql. Existing DB_* values were left unchanged (use -ForceDbConfig to overwrite)."
        }

        return
    }

    throw "Unsupported database: $Db"
}

function Ensure-AppKey() {
    $envPath = ".env"

    if ($DryRun) {
        if (-not (Test-Path -LiteralPath $envPath)) {
            Write-Host "(dry-run) Would generate APP_KEY (php artisan key:generate)" -ForegroundColor DarkGray
            return
        }
    }

    $envText = Get-Content -LiteralPath $envPath -Raw
    if ($envText -match "(?m)^APP_KEY=.+$") {
        Write-Ok "APP_KEY already set"
        return
    }

    Invoke-Step -Label "Generate app key" -Command "php" -Arguments @("artisan", "key:generate")
}

function Main() {
    Write-Section "TSHSEMS Setup"

    Assert-FileExists "artisan" "This doesn't look like a Laravel project root."
    Assert-FileExists "composer.json" "Missing composer.json."
    Assert-FileExists "package.json" "Missing package.json."

    Write-Section "Prerequisites"
    Assert-CommandExists -Name "php" -InstallHint "Install PHP 8.2+ and ensure it's on PATH."
    if (-not $SkipComposer) {
        Assert-CommandExists -Name "composer" -InstallHint "Install Composer and ensure it's on PATH."
    }
    if (-not $SkipNpm) {
        Assert-CommandExists -Name "node" -InstallHint "Install Node.js 18+ and ensure it's on PATH."
        Assert-CommandExists -Name "npm" -InstallHint "Install Node.js (includes npm) and ensure it's on PATH."
    }

    Write-Section "Environment"
    Ensure-EnvFile
    Configure-Database -Db $Database

    if ($Database -eq 'mysql' -and -not $SkipMySqlChecks) {
        Check-MySqlServer
    }

    if (-not $SkipComposer) {
        Write-Section "PHP Dependencies"
        Invoke-Step -Label "composer install" -Command "composer" -Arguments @("install")
    }

    if (-not $SkipNpm) {
        Write-Section "JS Dependencies"
        Invoke-Step -Label "npm install" -Command "npm" -Arguments @("install")
    }

    Write-Section "Laravel Key"
    Ensure-AppKey

    if (-not $SkipMigrate) {
        Write-Section "Database Migrations"
        if ($Fresh) {
            $args = @("artisan", "migrate:fresh")
            if ($Seed -or $Seed.IsPresent) { $args += "--seed" }
            Invoke-Step -Label "migrate:fresh" -Command "php" -Arguments $args
        } else {
            Invoke-Step -Label "migrate" -Command "php" -Arguments @("artisan", "migrate")
            if ($Seed) {
                Invoke-Step -Label "db:seed" -Command "php" -Arguments @("artisan", "db:seed")
            }
        }
    }

    Write-Section "Laravel Storage"
    Invoke-Step -Label "storage:link (for file uploads)" -Command "php" -Arguments @("artisan", "storage:link")

    if (-not $SkipBuild) {
        Write-Section "Frontend Build"
        Invoke-Step -Label "npm run build" -Command "npm" -Arguments @("run", "build")
    }

    if (-not $SkipServe) {
        Write-Section "Run Dev Server"
        Write-Warn "Starting servers in the foreground. Use Ctrl+C to stop."
        Write-Host "" 
        Write-Host "Laravel: http://localhost:$Port" -ForegroundColor Green
        Write-Host "" 

        if ($DryRun) {
            Write-Host "(dry-run) php artisan serve --port=$Port" -ForegroundColor DarkGray
            Write-Host "(dry-run) In another terminal: npm run dev" -ForegroundColor DarkGray
            return
        }

        # Start Laravel server in this terminal
        & php artisan serve --port=$Port
    } else {
        Write-Ok "Setup complete (servers not started)."
        Write-Host "Next:" -ForegroundColor Cyan
        Write-Host "  php artisan serve" -ForegroundColor DarkGray
        Write-Host "  npm run dev" -ForegroundColor DarkGray
    }
}

try {
    if (-not $PSBoundParameters.ContainsKey('Fresh')) { $Fresh = $true }
    if (-not $PSBoundParameters.ContainsKey('Seed')) { $Seed = $true }

    Main
} catch {
    Write-Host "" 
    Write-Host "SETUP FAILED" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red

    Write-Host "" 
    Write-Host "Tips:" -ForegroundColor Yellow
    Write-Host "- Run with -DryRun to preview commands" -ForegroundColor DarkGray
    Write-Host "- If using MySQL, confirm DB_* settings in .env" -ForegroundColor DarkGray
    Write-Host "- Ensure PHP extensions for your DB are enabled (pdo_sqlite or pdo_mysql)" -ForegroundColor DarkGray

    exit 1
}
