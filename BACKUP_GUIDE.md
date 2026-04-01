# Database Backup Guide - TSHSEMS

## Overview
The TSHSEMS backup system allows Technical Admins and Super Admins to create, download, and manage database backups to prevent data loss and ensure system recovery capability.

---

## Access the Backup Feature

### For Technical Admin:
1. Log in with your **Technical Admin** account
2. Navigate to **Database Backups** (left sidebar under "System Management")
3. You'll see the backup management interface

### For Super Admin:
1. Log in with your **Super Admin** account
2. Navigate to **Dashboard** → see the "System Status" card showing last backup
3. Or go to **Database Backups** in the sidebar (available under admin modules)

---

## How to Create a Backup

### Step 1: Navigate to Backups Page
- Click **Database Backups** in the left sidebar

### Step 2: Click "Create New Backup"
- Click the green **"Create New Backup"** button in the top right

### Step 3: Wait for Completion
- The system will create a backup of your entire database
- You'll see a success message once it's done
- The backup will appear in the list below

### What Gets Backed Up?
- ✅ All students data
- ✅ All teachers data
- ✅ All grades and scores
- ✅ All attendance records
- ✅ All announcements and documents
- ✅ All user accounts and settings
- ✅ Complete database structure

### Backup File Details
- **File Format**: `.sqlite` (SQLite database file)
- **Size**: ~320 KB to several MB depending on data volume
- **Naming Pattern**: `backup-YYYY-MM-DD-HHMMSS.sqlite`
  - Example: `backup-2026-02-16-143022.sqlite`
- **Location**: Stored securely in `storage/app/backups/`

---

## How to Download a Backup

### Step 1: Find Your Backup
- Go to **Database Backups** page
- Locate the backup you want in the table
- Look for the date created and file size

### Step 2: Click Download
- Click the blue **"Download"** button in the Actions column
- The backup file will be downloaded to your computer's downloads folder
- Keep it in a safe location on an external drive or cloud storage

### Why Download Backups?
- 🔒 **Disaster Recovery**: Keep off-site copies for system failures
- 📦 **Archival**: Store historical backups for compliance
- 🔐 **Security**: Backup to encrypted external drive
- 📋 **Audit Trail**: Maintain dated copies for auditing

---

## How to Delete a Backup

### Step 1: Find the Backup
- Go to **Database Backups** page
- Locate the backup in the table

### Step 2: Click Delete
- Click the red **"Delete"** button in the Actions column
- A confirmation dialog will appear

### Step 3: Confirm Deletion
- Click "OK" to permanently delete the backup
- The backup will be removed from the system

### ⚠️ Important Notes
- **Cannot be undone**: Deletion is permanent
- **Download first**: Always download backups before deleting if you need them
- **Keep recent copies**: Maintain at least 3 recent backups on the system

---

## How to Restore a Backup

### When to Restore
- 🔴 **System failure** or data corruption
- 🔄 **Accidental data deletion**
- 🐛 **Critical bug** that damaged data
- 📊 **Rollback to previous state** for compliance

### Restore Steps (For Technical Admin)

#### Option 1: Manual Restoration (Recommended)

1. **Download the backup**
   - From Database Backups page, download the `.sqlite` file to your computer

2. **Stop the application** (if possible)
   - This prevents new data from being written during restoration

3. **Replace the database file**
   - Navigate to: `C:\Users\[YourUser]\Documents\ITECC06\TSHSEMS\database\`
   - Locate: `database.sqlite` (the current active database)
   - **Backup the current file**: Rename it to `database.sqlite.backup-current`
   - **Replace with downloaded backup**: Copy the downloaded `.sqlite` file into this directory
   - **Rename it**: Rename it to `database.sqlite`

4. **Restart the application**
   - Clear browser cache (Ctrl+Shift+Delete)
   - Log back in and verify data has been restored

#### Option 2: Command Line Restoration

```bash
# Navigate to project
cd C:\Users\[YourUser]\Documents\ITECC06\TSHSEMS

# Backup current database
copy database\database.sqlite database\database.sqlite.backup-current

# Restore from backup
copy storage\app\backups\backup-2026-02-16-HHMMSS.sqlite database\database.sqlite

# Restart Laravel
php artisan serve
```

---

## Dashboard Backup Status

### System Status Card
The Admin Dashboard shows a **System Status** card that displays:

- **Status**: "Operational" (if system is running)
- **Last Backup**: Shows how long ago the most recent backup was created
  - Example: "Last backup: 2 hours ago"
  - Example: "Last backup: 1 day ago"

### Color Indicators
- 🟢 **Green**: System is operational with recent backups
- 🔴 **Red**: "No backups found" - CREATE A BACKUP IMMEDIATELY

---

## Best Practices

### Backup Schedule
| Frequency | Recommended | Use Case |
|-----------|-------------|----------|
| **Daily** | ✅ Yes (every morning) | Active system with regular changes |
| **Weekly** | ✅ Yes (every Monday) | Archive important snapshots |
| **Monthly** | ✅ Yes (end of month) | Compliance and archival |
| **Before Changes** | ✅ Yes | Before major updates or configurations |

### Storage Strategy
1. **Keep 3-5 recent backups** on the server (daily)
2. **Download and store weekly** backups on external drive
3. **Archive monthly** backups to cloud storage (OneDrive, Google Drive, etc.)
4. **Test restoration** quarterly to ensure backups work

### Naming Convention for Downloaded Backups
```
TSHSEMS-backup-2026-02-16-daily.sqlite
TSHSEMS-backup-2026-W07-weekly.sqlite
TSHSEMS-backup-2026-02-monthly.sqlite
```

---

## Troubleshooting

### Problem: "No backups found" on Dashboard
**Solution:**
- Go to **Database Backups** page
- Click **"Create New Backup"** button
- Wait 30 seconds for it to complete
- Refresh the Dashboard page

### Problem: Backup Creation Fails
**Solutions:**
- Check disk space: Ensure at least 1 GB free space in `C:\Users\[YourUser]\Documents\ITECC06\TSHSEMS\storage\`
- Check permissions: Technical Admin account must have write permissions to storage folder
- Check database file: Verify `database\database.sqlite` exists and is not corrupted

### Problem: Backup File Size is 0 KB
**Cause:** The backup creation failed (common for MySQL on Windows)
- These files are automatically ignored and won't appear in the list
- Delete them manually from `storage\app\backups\` if needed
- Create a new backup

### Problem: Cannot Download Backup
**Solutions:**
- Check browser permissions for downloads
- Ensure popup blockers aren't preventing download
- Try a different browser
- Ensure the backup file still exists (it may have been deleted)

---

## Security Considerations

### Backup Storage Locations
| Location | Security | Recommended |
|----------|----------|-------------|
| **Server** | 🟡 Medium | Daily (short-term) |
| **External USB Drive** | 🟢 High | Weekly (encrypted drive) |
| **Cloud Storage** | 🟢 High | Monthly (password-protected) |
| **Network Share** | 🟡 Medium | If password-protected |

### Protection Tips
- 🔒 **Encrypt** backups before storing on USB drives
- 🔐 **Password-protect** cloud storage accounts
- 🚫 **Don't share** backup files via email
- 📝 **Document** location and access procedures
- ⏰ **Test** restoration annually

---

## Database Information

### Current Database System
- **Type**: SQLite (via `database\database.sqlite`)
- **Backup Extension**: `.sqlite`
- **Backup Method**: Direct file copy
- **Size**: Typically 320 KB - 5 MB

### If You Switch to MySQL
- **Backup Type**: `.sql` (SQL script)
- **Backup Method**: `mysqldump` command
- **Restoration**: `mysql` command or phpMyAdmin
- The backup system will automatically detect and use the correct method

---

## Quick Reference

| Action | Steps | Time |
|--------|-------|------|
| **Create Backup** | Click button → Wait | ~10 seconds |
| **Download Backup** | Find → Click download | ~5 seconds |
| **Delete Backup** | Find → Click delete → Confirm | ~2 seconds |
| **Restore Backup** | Download → Replace file → Restart | ~5 minutes |

---

## Support & Help

For issues or questions:
1. Check the **Backup Guidelines** section on the Database Backups page
2. Review this guide's "Troubleshooting" section
3. Contact your System Administrator or Technical Admin
4. Check `storage\logs\laravel.log` for detailed error messages

---

**Last Updated**: February 16, 2026  
**Document Version**: 1.0  
**Applicable To**: TSHSEMS v1.0+
