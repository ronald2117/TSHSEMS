<!-- Sidebar Navigation -->
<div class="w-64 bg-white shadow-sm flex flex-col">
    <!-- Logo Section -->
    <div class="flex p-6 border-b border-gray-100">
        <img src="{{ asset('tshs_logo.png') }}" alt="tshs_logo" class="mr-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900">TSHSEMS</h1>
            <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            @if(auth()->user()->isStudent())
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('student.profile.index') }}" :active="request()->routeIs('student.profile.*')" icon="user">
                    My Profile
                </x-nav-link>
                <x-nav-link href="{{ route('student.grades.index') }}" :active="request()->routeIs('student.grades.*')" icon="academic-cap">
                    My Grades
                </x-nav-link>
                <x-nav-link href="{{ route('student.attendance.index') }}" :active="request()->routeIs('student.attendance.*')" icon="calendar">
                    Attendance
                </x-nav-link>
                <x-nav-link href="{{ route('student.schedule.index') }}" :active="request()->routeIs('student.schedule.*')" icon="clock">
                    My Schedule
                </x-nav-link>
                <x-nav-link href="{{ route('student.announcements.index') }}" :active="request()->routeIs('student.announcements.*')" icon="bell">
                    Announcements
                </x-nav-link>
                <x-nav-link href="{{ route('student.documents.index') }}" :active="request()->routeIs('student.documents.*')" icon="document">
                    Document Requests
                </x-nav-link>

            @elseif(auth()->user()->isTeacher())
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('teacher.classes.index') }}" :active="request()->routeIs('teacher.classes.*')" icon="book-open">
                    My Classes
                </x-nav-link>
                <x-nav-link href="{{ route('teacher.grading.index') }}" :active="request()->routeIs('teacher.grading.*')" icon="pencil">
                    Grading
                </x-nav-link>
                <x-nav-link href="{{ route('teacher.assessments.index') }}" :active="request()->routeIs('teacher.assessments.*')" icon="clipboard-list">
                    Assessments
                </x-nav-link>
                <x-nav-link href="{{ route('teacher.attendance.index') }}" :active="request()->routeIs('teacher.attendance.*')" icon="calendar">
                    Attendance
                </x-nav-link>

            @elseif(auth()->user()->isAdmin())
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-nav-link>
                
                @if(auth()->user()->role === 'academic_admin')
                    <x-nav-link href="{{ route('admin.students.index') }}" :active="request()->routeIs('admin.students.*')" icon="academic-cap">
                        Students
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.teachers.index') }}" :active="request()->routeIs('admin.teachers.*')" icon="briefcase">
                        Teachers
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.school-years.index') }}" :active="request()->routeIs('admin.school-years.*')" icon="calendar">
                        School Years
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.academic-periods.index') }}" :active="request()->routeIs('admin.academic-periods.*')" icon="calendar">
                        Academic Periods
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.tracks.index') }}" :active="request()->routeIs('admin.tracks.*')" icon="book-open">
                        Tracks
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.strands.index') }}" :active="request()->routeIs('admin.strands.*')" icon="book-open">
                        Strands
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.sections.index') }}" :active="request()->routeIs('admin.sections.*')" icon="building">
                        Sections
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.subjects.index') }}" :active="request()->routeIs('admin.subjects.*')" icon="book-open">
                        Subjects
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.class-schedules.index') }}" :active="request()->routeIs('admin.class-schedules.*')" icon="calendar">
                        Class Schedules
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.*')" icon="bell">
                        Announcements
                    </x-nav-link>
                @endif

                @if(auth()->user()->role === 'registrar_admin')
                    <x-nav-link href="{{ route('admin.students.index') }}" :active="request()->routeIs('admin.students.*')" icon="academic-cap">
                        Students
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.enrollment.index') }}" :active="request()->routeIs('admin.enrollment.*')" icon="clipboard-check">
                        Enrollment
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.grade-approval.index') }}" :active="request()->routeIs('admin.grade-approval.*')" icon="check-circle">
                        Grade Approval
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.documents.index') }}" :active="request()->routeIs('admin.documents.*')" icon="document">
                        Document Requests
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')" icon="chart-bar">
                        Reports & Forms
                    </x-nav-link>
                @endif

                @if(auth()->user()->role === 'technical_admin')
                    <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="users">
                        User Management
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.backups.index') }}" :active="request()->routeIs('admin.backups.*')" icon="database">
                        Database Backups
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.logs.activity') }}" :active="request()->routeIs('admin.logs.activity')" icon="clipboard-list">
                        Activity Logs
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.logs.grades') }}" :active="request()->routeIs('admin.logs.grades')" icon="chart-bar">
                        Grade Audit Logs
                    </x-nav-link>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <x-nav-link href="{{ route('admin.all-navigations') }}" :active="request()->routeIs('admin.all-navigations')" icon="view-grid">
                        All Admin Navigations
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.system.stats') }}" :active="request()->routeIs('admin.system.stats')" icon="chart-square-bar">
                        System Statistics
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.*')" icon="bell">
                        Announcements
                    </x-nav-link>
                @endif
            @endif
        </div>
    </nav>
</div>
