
# TSHSEMS (Taysan Senior High School Evaluation Management System)

TSHSEMS is a Laravel 12 web application that digitizes academic evaluation, grading, and student management for senior high schools aligned with DepEd curriculum workflows.

## Key Features

- Role-based access control (Super Admin, Academic Admin, Registrar Admin, Technical Admin, Teacher, Student)
- DepEd-style grading pipeline (assessment types, weighting, grade transmutation, remarks)
- Grade approval workflow (Draft → Submitted → Approved/Returned) with audit logging
- Attendance recording and student attendance viewing
- Academic structure management (school years, periods, tracks/strands, sections, subjects, class schedules)
- Modern responsive UI (Blade + Tailwind + Vite)

## Tech Stack

- Backend: Laravel 12 (PHP 8.2+)
- Frontend: Blade + Tailwind CSS + Vite
- Database: SQLite (recommended for local) or MySQL
- Tests: Pest

## Quick Start (Windows)

### Option A — Run the PowerShell setup script (recommended)

From the project root:

```powershell
powershell -ExecutionPolicy Bypass -File scripts\setup.ps1
```

### Option B — Manual setup

#### 1) Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ (includes npm)
- Database:
	- SQLite (recommended for local), or
	- MySQL (if using MySQL, ensure it’s running and credentials match your `.env`)

#### 2) Install dependencies

```bash
composer install
npm install
```

#### 3) Create environment file

PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

CMD:

```bat
copy .env.example .env
php artisan key:generate
```

macOS/Linux (or Git Bash):

```bash
cp .env.example .env
php artisan key:generate
```

#### 4) Configure database

SQLite (recommended):

- Create the DB file: `database/database.sqlite`
- In `.env`, set:
	- `DB_CONNECTION=sqlite`
	- `DB_DATABASE=ABSOLUTE_PATH_TO\database\database.sqlite`

MySQL:

- In `.env`, set `DB_CONNECTION=mysql` and update `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

#### 5) Run migrations + seed demo data

```bash
php artisan migrate:fresh --seed
```

#### 6) Build frontend assets

```bash
npm run build
```

#### 7) Run dev servers

Terminal 1:

```bash
php artisan serve
```

Terminal 2 (optional, for hot reload during development):

```bash
npm run dev
```

Open: http://localhost:8000

## Demo Login Credentials (Seeded)

Use either **email** or **login_id** in the login field.

| Role | Email | Password | Login ID |
|------|-------|----------|----------|
| Super Admin | admin@tshsems.local | password123 | ADMIN001 |
| Academic Admin | academic@tshsems.local | password123 | ACAD001 |
| Registrar Admin | registrar@tshsems.local | password123 | REG001 |
| Teacher 1 | teacher1@tshsems.local | password123 | T-2025-0001 |
| Teacher 2 | teacher2@tshsems.local | password123 | T-2025-0002 |
| Student 1 | student1@tshsems.local | password123 | LRN000000001 |
| Student 2 | student2@tshsems.local | password123 | LRN000000002 |

## Useful Commands

```bash
# Run tests
./vendor/bin/pest

# Reset DB and reseed demo data
php artisan migrate:fresh --seed

# Clear caches
php artisan optimize:clear
```

## Troubleshooting

- Migrations fail on SQLite: ensure the `pdo_sqlite` PHP extension is enabled.
- Migrations fail on MySQL: ensure `pdo_mysql` is enabled and MySQL is running.
- Autoload / class errors: `composer dump-autoload`
- Config/cache weirdness: `php artisan optimize:clear`

## Docs

- `QUICK_START.md` (short setup)
- `PROTOTYPE_SETUP.md` (full prototype overview)
- `.github/copilot-instructions.md` (architecture + codebase conventions)

