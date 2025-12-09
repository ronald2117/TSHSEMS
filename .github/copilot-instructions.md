# TSHSEMS Codebase Guide for AI Agents

## Project Overview
**TSHSEMS** (Taysan Senior High School Evaluation Management System) is a Laravel 12 web application that digitizes academic evaluation, grading, and student management for senior high schools following DepEd curriculum standards.

## Core Architecture

### Role-Based System (6 Roles)
The application enforces multi-role access control. Every user has a single `role` enum field:
- **Super Admin**: System override, manages all admin accounts, posts public announcements
- **Academic Admin**: Manages teachers, academic structure (school years, strands, sections, subjects)
- **Registrar Admin**: Manages students, enrollment, grade approval workflow, document requests
- **Technical Admin**: Database backups, audit logs, password resets
- **Teacher**: Inputs grades, records attendance, views assigned classes
- **Student**: Views approved grades and attendance, requests documents

See `app/Models/User.php` for role check methods (`isAdmin()`, `isSuperAdmin()`, etc.).

### Critical Data Flow: Academic Structure
```
School Year (e.g. 2025-2026)
├── Academic Period (e.g. 1st Semester)
└── Sections (e.g. Grade 11 Diamond)
    ├── Strand (e.g. STEM, ABM, HUMSS, etc.)
    ├── Adviser
    └── Class Schedules (Subject + Teacher assignments)
        └── Student Subject Enrollments
            └── Assessments → Student Scores → Quarterly Grades
```

Key tables: `school_years`, `academic_periods`, `sections`, `strands`, `class_schedules`, `student_subject_enrollments`.

### Grading Engine (DepEd Compliant)
The system implements a sophisticated grading pipeline:

1. **Assessment Types** (`assessments` table): Written Work, Performance Task, Quarterly Assessment
2. **Score Recording** (`student_scores`): Raw scores per assessment per student
3. **Component Weighting** (`grading_components`): Different weights for Core/Applied/Specialized subjects
   - Written: 25%, Performance: 50%, Exam: 25% (configurable per subject type)
4. **Grade Computation**: Weighted average of component averages → initial_grade
5. **Grade Transmutation** (`grade_transmutations`): Converts percentage to 60-100 scale → final_grade
6. **Approval Workflow** (`quarterly_grades`): Draft → Submitted → Approved/Returned (Registrar approval)
7. **GWA Caching** (`student_gwa_cache`): Cached General Weighted Average with honors computation

**Important**: Grade changes must be logged in `grade_audit_logs` with reason and user ID for compliance.

## Developer Workflows

### Database & Migrations
- Create migrations with `php artisan make:migration [name]`
- Migrations live in `database/migrations/`
- Current schema documented in `database/schema.dbml` (dbdiagram.io format)
- Run migrations: `php artisan migrate` (add `--force` in production)

### Testing
- Test framework: **Pest** (configured in `phpunit.xml`)
- Test directories: `tests/Feature/` and `tests/Unit/`
- Run tests: `./vendor/bin/pest` or `php artisan test`
- SQLite in-memory DB for tests (see `phpunit.xml`)

### Frontend Build
- Build tool: **Vite** with Laravel plugin
- Bundler: Tailwind CSS + Laravel Vite Plugin
- Dev server: `npm run dev` (hot reload for `resources/css/app.css` and `resources/js/app.js`)
- Production build: `npm run build`
- Output: `public/build/` with manifest

### Artisan Commands
- Key setup: `php artisan key:generate`
- Database seeding: `php artisan seed` (configure in `database/seeders/DatabaseSeeder.php`)
- List all commands: `php artisan list`

## Design System & UI Conventions

### Visual Design Specification
The application uses a **modern, minimalist admin dashboard** with Material Design-inspired aesthetics and 'Flat 2.0' styling:

**Color Palette:**
- **Background**: White and light gray (`slate-50`)
- **Primary Accent**: Green (`#22c55e`) for active states, buttons, and branding
- **Typography**: Sans-serif (Inter or Roboto) for all text
- **Spacing**: Generous whitespace throughout

**Layout Components:**
1. **Persistent Left Sidebar Navigation**
   - High-contrast icons
   - Active state: light green background with solid left border strip
   - Scrollable for long navigation lists

2. **Top Header Bar**
   - Breadcrumb title/current page
   - Notification bell icon
   - User profile dropdown

3. **Content Toolbar** (below header)
   - Search input
   - Filter dropdowns
   - Grid/list view toggle

4. **Content Cards**
   - White background with `rounded-xl` corners
   - Soft drop shadow (`shadow-sm`)
   - Internal structure: circular avatar, header text, subtitle, text-based action button with icon
   - Responsive grid layout

**Responsiveness:** Cards and layout must be fully responsive across desktop, tablet, and mobile devices.

## Project-Specific Patterns

### Model Conventions
1. **Relationships**: Always use typed return hints (e.g., `BelongsTo`, `HasMany`, `HasOne`)
2. **Scopes**: Chainable query helpers (e.g., `StudentProfile::forStrand($strandId)->get()`)
3. **Attribute Casting**: Use `casts()` method for dates/decimals (e.g., `'score' => 'decimal:2'`)
4. **Soft Deletes**: Used on `User`, `Subject`, `Assessment`, `ClassSchedule`, `QuarterlyGrade` for audit trails
5. **Fillable Arrays**: Whitelist columns allowed in mass assignment (see `Student`, `Announcement`, etc.)

### Grade Calculation (Real Pattern)
```php
// 1. Get assessments by type
$written = Assessment::where('type', 'written_work')->get();
$performance = Assessment::where('type', 'performance_task')->get();
$exam = Assessment::where('type', 'quarterly_assessment')->first();

// 2. Compute weighted averages
$weights = GradingComponent::where('subject_type', $subjectType)->first();
$initial = ($writtenAvg * $weights->written_weight) + 
           ($perfAvg * $weights->performance_weight) + 
           ($examScore * $weights->exam_weight);

// 3. Transmute to final grade
$finalGrade = GradeTransmutation::transmute($initial);
$remarks = GradeTransmutation::getRemarks($finalGrade); // "Passed" or "Failed"

// 4. Compute GWA (average of all final_grades)
$gwa = QuarterlyGrade::where('student_id', $studentId)
    ->where('quarter', $quarter)
    ->avg('final_grade');
$honors = GradeTransmutation::getHonors($gwa);
```

### Announcement Targeting
Announcements use `is_public` (for landing page) and `target_role` (for dashboard):
```php
// Public landing page announcements
Announcement::public()->pinned()->get();

// Role-specific dashboard announcements
Announcement::forRole('student')->published()->get();
```

## Integration Points & Cross-Component Communication

### Authentication & Authorization
- Laravel's built-in `Auth` facade with custom role checks
- Guard: `web` (session-based)
- All protected routes should check role in middleware or policy (not yet implemented)

### Audit Trail Requirements
- **Grade changes**: Must log to `grade_audit_logs` (user_id, old_grade, new_grade, reason, ip_address)
- **System activities**: Use `activity_logs` table (action, description, ip_address)
- **Soft deletes**: Automatically preserved in `deleted_at` column for compliance

### Key External Dependencies
- **Laravel Framework 12**: Core MVC, ORM (Eloquent), routing, validation, migrations
- **Spatie Permission**: Role/permission package (already in `composer.json`, but roles currently use simple enum)
- **FakerPHP**: Data generation in seeders for testing
- **Pest**: Testing framework with Laravel plugin

## File Organization & Key Locations

| Path | Purpose |
|------|---------|
| `app/Models/` | Eloquent models (User, StudentProfile, QuarterlyGrade, Assessment, etc.) |
| `app/Http/Controllers/` | Route handlers (Admin/, Teacher/, Student/ subdirectories empty—routes need building) |
| `database/migrations/` | Schema definitions; current: `2025_11_29_054733_create_tshsems_table.php` |
| `routes/web.php` | Route definitions (currently minimal—needs expansion) |
| `resources/views/` | Blade templates organized by role (admin/, teacher/, student/, public/) |
| `resources/css/app.css` | Tailwind base styles |
| `resources/js/app.js` | Frontend JavaScript entry point |
| `config/` | App configuration (database, cache, auth, mail, etc.) |

## Common Pitfalls & Best Practices

1. **Grading Precision**: Always use `decimal(5, 2)` for scores and grades to avoid floating-point errors
2. **Soft Deletes**: When querying, remember to use `->withTrashed()` or exclude soft-deleted records with `->whereNull('deleted_at')`
3. **Enrollment Constraints**: A student can only enroll in ONE section per school year (enforced in `student_profiles.current_section_id`)
4. **Quarter Validation**: Quarters are 1-4 only (`->where('quarter', '<=', 4)`)
5. **Status Workflow**: Quarterly grades flow Draft → Submitted → Approved. Always validate state transitions.
6. **Role-Based Access**: Don't hardcode role checks—use middleware or authorization policies for consistency

## Testing Approach
- Pest uses simple test syntax: `test('description', fn() => expect(...)->toBe(...))`
- Use database transactions in tests to avoid cleanup (see `phpunit.xml` with SQLite in-memory)
- Seed test data with factories: `User::factory()->create(['role' => 'teacher'])`

---

**Last Updated**: December 7, 2025 | **Framework**: Laravel 12 | **Build**: Vite + Tailwind | **Testing**: Pest
