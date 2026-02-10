# TSHSEMS - Complete Prototype Setup Guide

## 🎯 What's Included

This is a fully functional prototype of the TSHSEMS (Taysan Senior High School Evaluation Management System) with the following features:

### ✅ Completed Components

1. **Database & Models** ✓
   - Complete schema with all tables for academic structure, grading, and attendance
   - All Eloquent models with proper relationships and scopes
   - Migrations ready to run

2. **Authentication System** ✓
   - Hybrid login (email or login_id)
   - Role-based user creation
   - Session management
   - Password reset (scaffold ready)

3. **Role-Based Access Control** ✓
   - 6 roles: Super Admin, Academic Admin, Registrar Admin, Technical Admin, Teacher, Student
   - Middleware protection for each role
   - Proper authorization checks

4. **Admin Modules** ✓
   - User Management (create, edit, disable users)
   - Academic Structure (School Years, Sections, Strands, Subjects)
   - Grade Approval Workflow (approve, return, override grades)
   - Audit logging for grade changes

5. **Teacher Modules** ✓
   - Class management and student rosters
   - Grading interface with score entry
   - Grade submission workflow
   - Attendance recording
   - Dashboard with statistics

6. **Student Modules** ✓
   - View approved grades by subject and quarter
   - Attendance tracking with summary
   - Dashboard with academic overview
   - Responsive design

7. **Frontend Design** ✓
   - Modern, minimalist admin dashboard
   - Material Design-inspired UI
   - Green accent color (#22c55e) throughout
   - Responsive card-based layout
   - Tailwind CSS + Vite build setup

8. **Database Seeder** ✓
   - Comprehensive test data with:
     - 3 admin users
     - 5 teachers
     - 20 students
     - Complete academic structure (school years, sections, strands, subjects)
     - Class schedules with student enrollments
     - Assessments with scores
     - Quarterly grades (sample data)
     - Attendance records

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite or MySQL

### Installation Steps

1. **Clone and navigate to project**
   ```bash
   cd /home/ronald2117/Documents/Github/TSHSEMS
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Create database and run migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed with test data**
   ```bash
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **In another terminal, start Vite dev server**
   ```bash
   npm run dev
   ```

Access the application at `http://localhost:8000`

## 📋 Test Credentials

### Admin Users
- **Super Admin**: `admin@tshsems.local` / `password123`
- **Academic Admin**: `academic@tshsems.local` / `password123`
- **Registrar Admin**: `registrar@tshsems.local` / `password123`

### Teachers
- `teacher1@tshsems.local` / `password123` (login_id: `T-2025-0001`)
- `teacher2@tshsems.local` / `password123` (login_id: `T-2025-0002`)
- ...etc

### Students
- `student1@tshsems.local` / `password123` (login_id: `LRN000000001`)
- `student2@tshsems.local` / `password123` (login_id: `LRN000000002`)
- ...etc

## 📁 File Structure

### Controllers
- `app/Http/Controllers/DashboardController.php` - Role-based dashboard routing
- `app/Http/Controllers/Auth/*` - Authentication (login, register, password reset)
- `app/Http/Controllers/Admin/*` - Admin features
- `app/Http/Controllers/Teacher/*` - Teacher features
- `app/Http/Controllers/Student/*` - Student features

### Views
- `resources/views/layouts/app.blade.php` - Main layout with sidebar
- `resources/views/layouts/sidebar.blade.php` - Navigation sidebar
- `resources/views/layouts/header.blade.php` - Top header bar
- `resources/views/auth/*` - Auth pages
- `resources/views/admin/*` - Admin pages
- `resources/views/teacher/*` - Teacher pages
- `resources/views/student/*` - Student pages
- `resources/views/components/*` - Reusable Blade components

### Routes
- `routes/web.php` - All application routes with middleware protection

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Admin role check
- `app/Http/Middleware/TeacherMiddleware.php` - Teacher role check
- `app/Http/Middleware/StudentMiddleware.php` - Student role check
- `app/Http/Middleware/RegistrarAdminMiddleware.php` - Registrar specific check

## 🎨 Design System

The application follows these design conventions:

### Color Palette
- **Background**: White (`#ffffff`) and light gray (`#f8fafc` - slate-50)
- **Primary Accent**: Green (`#22c55e` - emerald-500)
- **Secondary**: Various status colors (red for danger, yellow for warning, etc.)
- **Typography**: Inter or Roboto sans-serif

### Layout Components
1. **Persistent Left Sidebar** (64 units wide)
   - Logo/brand at top
   - Navigation links with active state (light green bg + left border)
   - User profile at bottom

2. **Top Header Bar**
   - Breadcrumb title and subtitle
   - Notification bell
   - User profile dropdown

3. **Content Toolbar** (optional per page)
   - Search input
   - Filter dropdowns
   - View toggle buttons

4. **Content Cards**
   - White background with `rounded-xl` corners
   - Soft drop shadow (`shadow-sm`)
   - Hover effect: increased shadow
   - Responsive grid layout (1 col mobile → 4 cols desktop)

## 🔄 Key Workflows

### Grade Management Flow
1. **Teacher**: Enters assessment scores for written work, performance tasks, and exams
2. **Teacher**: Grades are auto-calculated (weighted average)
3. **Teacher**: Submits grades for approval
4. **Registrar**: Reviews submitted grades
5. **Registrar**: Approves, returns, or overrides grades
6. **Student**: Views approved grades (after status = "Approved")
7. **Audit Log**: All changes tracked with user, old/new values, reason, IP

### Attendance Flow
1. **Teacher**: Records daily attendance for each class
2. **Teacher**: Views attendance summary by student
3. **Student**: Views personal attendance record with status breakdown
4. **Dashboard**: Shows Present/Absent/Late/Excused counts

### Academic Structure Flow
1. **Admin**: Creates/manages school years and activation
2. **Admin**: Creates academic periods (semesters)
3. **Admin**: Defines strands (STEM, ABM, etc.)
4. **Admin**: Creates sections (grade level + strand)
5. **Admin**: Assigns teachers to sections as advisers
6. **Admin**: Creates subjects and links to strands
7. **Admin**: Creates class schedules (subject + teacher + section + period)
8. **Admin**: Enrolls students in classes via class schedules

## 🧪 Testing

### Run Tests
```bash
./vendor/bin/pest
```

### Run Specific Test
```bash
./vendor/bin/pest tests/Feature/ExampleTest.php
```

Tests use SQLite in-memory database (see `phpunit.xml`).

## 📚 Key Features Implemented

### Grading Engine
- ✓ Assessment types (written work, performance task, exam)
- ✓ Component-based weighting (25%, 50%, 25%)
- ✓ Grade transmutation (percentage → 60-100 scale)
- ✓ Decimal precision (5,2) for accuracy
- ✓ Workflow status (Draft → Submitted → Approved → Published)
- ✓ Grade override with audit logging
- ✓ GWA calculation (average of all final grades)
- ✓ Honors computation (With Highest/High/Regular Honors)

### Soft Deletes
- ✓ Users, Subjects, Assessments, ClassSchedules, QuarterlyGrades all use soft deletes
- ✓ Preserved in database with `deleted_at` timestamp
- ✓ Queries automatically exclude soft-deleted records
- ✓ Use `->withTrashed()` to include soft-deleted records

### Role-Based Views
- Dashboard adapts based on logged-in user role
- Navigation sidebar shows role-specific links
- All routes protected with appropriate middleware
- Authorization checks in controllers

## 🔧 Configuration

### Important Files
- `.env` - Application environment variables
- `config/app.php` - Application configuration
- `config/auth.php` - Authentication settings
- `database/migrations/` - Database schema
- `vite.config.js` - Frontend build configuration

### Environment Variables
```env
APP_NAME="TSHSEMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
```

## 🚨 Common Issues & Solutions

### "Class not found" error
```bash
composer dump-autoload
```

### Assets not loading
```bash
npm run build
# or for development
npm run dev
```

### Database migration fails
```bash
# Fresh start
php artisan migrate:fresh --seed
```

### Session issues
```bash
php artisan cache:clear
php artisan config:clear
```

## 📝 Next Steps to Enhance

1. **Frontend Pages** - Create full CRUD views for:
   - Admin user management list/edit/create
   - School year management
   - Grade approval interface
   - Assessment management

2. **API Endpoints** - Build REST API for:
   - Mobile app integration
   - Real-time grade updates
   - Attendance sync

3. **Reports** - Implement report generation:
   - Form 137 (Permanent Record)
   - Grade cards/report cards
   - Class performance summaries
   - Attendance reports

4. **Email Notifications** - Send emails for:
   - Grade publication
   - Returned grades
   - Important announcements

5. **Advanced Features**:
   - Document request processing
   - Announcement system
   - Student profile editing
   - Parent portal integration

## 📞 Support

For questions about the codebase structure, refer to `.github/copilot-instructions.md` which contains detailed architecture documentation.

---

**Created**: December 2025
**Framework**: Laravel 12 | **Frontend**: Tailwind CSS + Vite | **Testing**: Pest
