# Migrating from SQLite to MySQL - TSHSEMS

## Current Situation
Your system is currently using **SQLite** (see `.env` line: `DB_CONNECTION=sqlite`)
- Backups create `.sqlite` files
- These won't work with MySQL

## Goal
Switch to **MySQL** for both development and production
- Future backups will create `.sql` files
- These `.sql` files work perfectly with MySQL

---

## Step-by-Step Migration Guide

### Step 1: Install MySQL (if not installed)

1. Download MySQL from: https://dev.mysql.com/downloads/mysql/
2. Install MySQL Community Server
3. Set a root password during installation (remember it!)
4. Add MySQL to PATH:
   - Location: `C:\Program Files\MySQL\MySQL Server 8.0\bin`

### Step 2: Create MySQL Database

Open Command Prompt or PowerShell:

```bash
# Connect to MySQL
mysql -u root -p

# Enter your password, then create database:
CREATE DATABASE tshsems CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create a dedicated user (optional but recommended):
CREATE USER 'tshsems_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON tshsems.* TO 'tshsems_user'@'localhost';
FLUSH PRIVILEGES;

# Exit MySQL
EXIT;
```

### Step 3: Update Your .env File

Replace the database section in `.env`:

**BEFORE (SQLite):**
```env
DB_CONNECTION=sqlite
DB_HOST=
DB_PORT=
DB_DATABASE=C:\Users\Library18\Documents\ITECC06\TSHSEMS\database\database.sqlite
DB_USERNAME=
DB_PASSWORD=
```

**AFTER (MySQL):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tshsems
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### Step 4: Export Your Current SQLite Data

Before switching, save your current data:

```bash
# Navigate to project
cd C:\Users\Library18\Documents\ITECC06\TSHSEMS

# Export SQLite data to SQL format
php artisan tinker
```

Then in Tinker:
```php
// Get all tables
DB::select("SELECT name FROM sqlite_master WHERE type='table'");

// You'll need to export data manually or use a migration script
```

**OR** use a tool like **DB Browser for SQLite** to export as SQL.

### Step 5: Clear Config Cache

```bash
cd C:\Users\Library18\Documents\ITECC06\TSHSEMS

# Clear all cached configuration
php artisan config:clear
php artisan cache:clear
```

### Step 6: Run Database Migrations

```bash
# This will create all tables in MySQL
php artisan migrate:fresh
```

⚠️ **Warning**: `migrate:fresh` drops all tables first. Any existing data will be lost!

### Step 7: (Optional) Seed Test Data

If you want sample data:
```bash
php artisan db:seed
```

### Step 8: Verify MySQL Connection

```bash
# Check database connection
php artisan tinker --execute="echo 'Connected to: ' . DB::connection()->getDatabaseName();"
```

Should show: `Connected to: tshsems`

### Step 9: Create Your First MySQL Backup

1. Go to **Admin Dashboard**
2. Navigate to **Database Backups**
3. Click **"Create New Backup"**
4. The file will now be: `backup-2026-02-16-HHMMSS.sql` (MySQL format!)

---

## Data Migration Options

### Option A: Fresh Start (Recommended for Development)
- Start with empty MySQL database
- Re-enter data or use seeders
- Clean slate for production setup

### Option B: Manual Data Transfer
1. Export SQLite to CSV files
2. Import CSV into MySQL using phpMyAdmin
3. Time-consuming but preserves all data

### Option C: Use Laravel Package
Install a migration tool:
```bash
composer require binaryk/laravel-sqlite-to-mysql
php artisan sqlite:to-mysql
```

### Option D: Custom Migration Script
Write a Laravel command to copy data table by table:
```php
// Example: Copy users table
$users = DB::connection('sqlite')->table('users')->get();
DB::connection('mysql')->table('users')->insert($users->toArray());
```

---

## Backup System Changes (Automatic)

Once you switch to MySQL, the backup system automatically changes behavior:

| Aspect | SQLite (Old) | MySQL (New) |
|--------|--------------|-------------|
| **Backup Method** | File copy | `mysqldump` command |
| **File Extension** | `.sqlite` | `.sql` |
| **File Type** | Binary database | Text SQL script |
| **File Size** | ~320 KB | ~200 KB - 1 MB |
| **Restoration** | Replace file | Import SQL script |
| **Compatible?** | SQLite only | MySQL/MariaDB |

---

## Testing Your Migration

### 1. Check Database Connection
```bash
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected successfully!';"
```

### 2. Verify Tables Exist
```bash
php artisan tinker --execute="print_r(DB::select('SHOW TABLES'));"
```

### 3. Test the Application
- Log in to admin account
- Check if all pages load
- Create a test student
- Verify data appears correctly

### 4. Create a Backup
- Go to Database Backups page
- Create backup
- Verify it creates a `.sql` file
- Download and inspect the SQL file (it should contain `CREATE TABLE` statements)

---

## Production Deployment

### For Production Server:

1. **Use .env.production**:
```env
DB_CONNECTION=mysql
DB_HOST=your-production-host.com
DB_PORT=3306
DB_DATABASE=tshsems_production
DB_USERNAME=tshsems_prod_user
DB_PASSWORD=strong_secure_password_here
```

2. **Use Managed MySQL** (Recommended):
   - Azure Database for MySQL
   - AWS RDS for MySQL
   - Google Cloud SQL
   - Digital Ocean Managed MySQL

3. **Automated Backups**:
   - Set up daily automated backups via cron job
   - Store backups off-site (cloud storage)
   - Test restoration quarterly

### Backup Automation Script:
```bash
# C:\backup-script.bat
cd C:\path\to\TSHSEMS
php artisan tinker --execute="app(\App\Http\Controllers\Admin\TechnicalAdminController::class)->createBackup();"
```

Schedule in Windows Task Scheduler to run daily at 2 AM.

---

## Rollback Plan

If something goes wrong during migration:

1. **Keep your SQLite backup safe!**
   ```bash
   copy database\database.sqlite database\database.sqlite.SAFE_BACKUP
   ```

2. **Revert .env back to SQLite**:
   ```env
   DB_CONNECTION=sqlite
   ```

3. **Clear cache and restart**:
   ```bash
   php artisan config:clear
   php artisan serve
   ```

---

## FAQ

### Q: Can I use both SQLite and MySQL?
**A:** Yes, but not simultaneously. Laravel can only use one default connection at a time.

### Q: Will old SQLite backups work after switching?
**A:** No. Old `.sqlite` backups only work with SQLite. Make new MySQL backups after switching.

### Q: Do I need to change any code?
**A:** No! Laravel handles the database abstraction. The backup system auto-detects the database type.

### Q: What's better for production: SQLite or MySQL?
**A:** **MySQL** is better for production because:
- Better for concurrent users
- More scalable for large datasets
- Better backup and recovery tools
- Industry standard for web applications

### Q: Can I test MySQL locally before production?
**A:** Yes! Follow this guide to set up MySQL in development first, test thoroughly, then deploy to production with confidence.

---

## Support

If you encounter issues:
1. Check MySQL is running: `mysql -u root -p`
2. Verify credentials in `.env`
3. Check `storage\logs\laravel.log` for errors
4. Test connection: `php artisan migrate:status`

---

**Ready to migrate?** Follow the steps above carefully, and your backup system will automatically create MySQL-compatible `.sql` files!

**Last Updated**: February 16, 2026
