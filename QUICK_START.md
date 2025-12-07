# TSHSEMS - Quick Start Guide

## 🎯 What You Have

A **fully functional prototype** of the Taysan Senior High School Evaluation Management System with:
- ✓ Complete database schema with migrations
- ✓ 20+ Eloquent models with relationships
- ✓ 10+ controllers for all functionality
- ✓ 20+ Blade views with modern design
- ✓ Comprehensive test data via seeder
- ✓ Modern, responsive UI with Tailwind CSS
- ✓ Role-based access control
- ✓ Grade management workflow
- ✓ Attendance tracking
- ✓ All routes configured

## ⚡ Get Running in 5 Minutes

### 1️⃣ Install Dependencies
```bash
composer install
npm install
```

### 2️⃣ Create Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3️⃣ Setup Database
```bash
php artisan migrate:fresh --seed
```

### 4️⃣ Build Frontend
```bash
npm run build
```

### 5️⃣ Run Development Servers

**Terminal 1 - Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Vite (for hot reload):**
```bash
npm run dev
```

✅ **Done!** Visit `http://localhost:8000`

## 🔐 Login Credentials

| User Type | Email | Password | Login ID |
|-----------|-------|----------|----------|
| Super Admin | `admin@tshsems.local` | `password123` | `ADMIN001` |
| Academic Admin | `academic@tshsems.local` | `password123` | `ACAD001` |
| Registrar Admin | `registrar@tshsems.local` | `password123` | `REG001` |
| Teacher 1 | `teacher1@tshsems.local` | `password123` | `T-2025-0001` |
| Teacher 2 | `teacher2@tshsems.local` | `password123` | `T-2025-0002` |
| Student 1 | `student1@tshsems.local` | `password123` | `LRN000000001` |
| Student 2 | `student2@tshsems.local` | `password123` | `LRN000000002` |

**Use either email or login_id to login** ✓

## 🗂️ What's Inside

### Views (20+)
- **Auth**: Login, Registration, Password Reset
- **Admin**: Dashboard, User Management, Grade Approval
- **Teacher**: Dashboard, Grading, Attendance
- **Student**: Dashboard, Grades, Attendance
- **Layouts**: Sidebar, Header, Base Layout

### Controllers (10+)
- `DashboardController` - Role-based routing
- `Auth/*` - Authentication (4 controllers)
- `Admin/*` - Admin features (3 controllers)
- `Teacher/*` - Teacher features (2 controllers)
- `Student/*` - Student features (2 controllers)

### Models (20+)
All models include proper relationships and scopes:
- User, StudentProfile, TeacherProfile
- SchoolYear, AcademicPeriod, Section, Strand, Track
- Subject, StrandSubject, ClassSchedule
- StudentSubjectEnrollment, Assessment, StudentScore
- QuarterlyGrade, GradeAuditLog, Attendance
- GradeTransmutation, GradingComponent

## 🎨 Design System

| Element | Color | Example |
|---------|-------|---------|
| Primary Accent | Green #22c55e | Buttons, active nav, highlights |
| Background | White, Light Gray | Card backgrounds, page bg |
| Text | Dark Gray | All typography |
| Status | Green/Red/Yellow/Blue | Badges, indicators |

Fully responsive (mobile → tablet → desktop)

## 📊 Features Implemented

### Grade Management ✓
- Teacher enters scores (written work, performance tasks, exams)
- Automatic calculation with DepEd-compliant weighting
- Grade transmutation (percentage → 60-100 scale)
- Registrar approval workflow
- Grade override with audit logging
- Complete change history

### Attendance ✓
- Teacher records daily attendance
- Multiple status types (Present, Absent, Late, Excused)
- Student views personal attendance history
- Monthly summary statistics

### Academic Structure ✓
- School years and academic periods
- Tracks and strands (STEM, ABM, etc.)
- Sections with adviser assignment
- Subjects with learning competencies
- Class schedules with room and time
- Student enrollment management

### User Management ✓
- 6 role types with different permissions
- Create/edit/disable users
- Role-specific dashboards
- Last login tracking

## 🔧 Common Commands

```bash
# Fresh database with test data
php artisan migrate:fresh --seed

# Run tests
./vendor/bin/pest

# Clear caches
php artisan cache:clear
php artisan config:clear

# Create new migration
php artisan make:migration create_table_name

# Generate new model
php artisan make:model ModelName

# Generate new controller
php artisan make:controller ControllerName
```

## 📱 Responsive Design

- **Mobile** (< 768px): 1-column layout, hamburger nav
- **Tablet** (768px - 1024px): 2-column cards
- **Desktop** (> 1024px): 3-4 column grids
- All views tested for responsiveness

## 🚀 Next Steps

### For Development
1. Review `.github/copilot-instructions.md` for architecture details
2. Check `PROTOTYPE_SETUP.md` for comprehensive setup guide
3. Read `IMPLEMENTATION_SUMMARY.md` for feature overview

### To Enhance
1. **Add more admin views** (edit/delete forms)
2. **Implement API endpoints** (for mobile app)
3. **Add email notifications** (grade publication)
4. **Generate reports** (Form 137, grade cards)
5. **Add dashboards with charts** (analytics)

### To Deploy
1. Update `.env` with production values
2. Run `php artisan migrate` on production database
3. Build assets: `npm run build`
4. Setup SSL/HTTPS
5. Configure environment variables
6. Set up automated backups

## 🐛 Troubleshooting

**"SQLSTATE[HY000]: General error"**
```bash
php artisan migrate:fresh --seed
```

**Assets not loading**
```bash
npm run build
# or
npm run dev
```

**"Class not found" errors**
```bash
composer dump-autoload
```

**Authentication issues**
```bash
php artisan cache:clear
php artisan config:clear
```

## 📚 Key Files to Understand

| File | Purpose |
|------|---------|
| `routes/web.php` | All application routes |
| `app/Models/User.php` | User model with role checks |
| `app/Http/Controllers/DashboardController.php` | Dashboard routing |
| `resources/views/layouts/app.blade.php` | Main layout |
| `resources/views/layouts/sidebar.blade.php` | Navigation |
| `database/seeders/DatabaseSeeder.php` | Test data |
| `.github/copilot-instructions.md` | Architecture guide |

## ✨ Highlights

✅ **Production-Ready Code**
- Clean, organized structure
- Proper error handling
- Database transactions where needed
- Comprehensive validation

✅ **DepEd Compliant Grading**
- Weighted component system
- Grade transmutation scale
- Audit trail for compliance
- Support for multiple quarters

✅ **Modern UI/UX**
- Minimalist design
- Consistent color scheme
- Smooth interactions
- Mobile-responsive

✅ **Developer Friendly**
- Clear naming conventions
- Type-hinted relationships
- Middleware-based protection
- Easy to extend

## 🎓 Learning Path

1. **Start**: Login as different roles to see how interface changes
2. **Explore**: Browse through admin, teacher, and student dashboards
3. **Test**: Try creating users, entering grades, recording attendance
4. **Review**: Check `.github/copilot-instructions.md` for architecture
5. **Extend**: Add new features based on requirements

---

**Status**: ✅ Ready to Use  
**Test Data**: ✅ Included  
**Documentation**: ✅ Complete  
**Next Steps**: Enhancement & Deployment  

🎉 **Happy Coding!**
