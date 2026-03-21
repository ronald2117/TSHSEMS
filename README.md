# TSHSEMS

A Laravel + Vite application.

## Prerequisites

Make sure you have the following installed on your local machine:
- PHP (>= 8.1)
- Composer
- Node.js & NPM
- A database server (MySQL, PostgreSQL, etc.) or just use SQLite.

## Installation & Setup

We have provided a convenient PowerShell script to set up the project automatically.

Simply open your PowerShell terminal and run:

```powershell
.\setup.ps1
```

### What the script does:
1. Checks for required dependencies (`php`, `composer`, `npm`).
2. Installs PHP dependencies (`composer install`).
3. Creates a `.env` file from `.env.example` and generates your application key.
4. Installs Node.js dependencies (`npm install`).
5. Builds your frontend Vite assets (`npm run build`).
6. Optionally runs the database migrations (`php artisan migrate`).

> **Note:** Make sure your `.env` is configured correctly (especially the `DB_*` variables) before running the database migrations.

## Local Development

To spin up the local development servers, you'll need two terminal windows:

Terminal 1 (Laravel backend server):
```bash
php artisan serve
```

Terminal 2 (Vite frontend server):
```bash
npm run dev
```

Visit `http://localhost:8000` in your browser.
