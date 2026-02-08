@extends('layouts.app')

@section('page_title', 'Admin Dashboard')

@section('page_subtitle')
    Welcome back, {{ auth()->user()->first_name }}! Here's your system overview.
@endsection

@section('content')
<div class="p-6 space-y-6">

    <!-- Summary Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students (Super Admin, Registrar) -->
        @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'registrar_admin' || auth()->user()->role === 'academic_admin')
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalStudents) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Enrolled this year</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- Active Teachers (Super Admin, Academic) -->
        @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'academic_admin')
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Active Teachers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalTeachers) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Teaching staff</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- Pending Grade Approvals (Registrar) -->
        @if((auth()->user()->role === 'registrar_admin' || auth()->user()->isSuperAdmin()) && isset($pendingGrades))
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pending Grades</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($pendingGrades) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting approval</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- Attendance Today (Academic, Super Admin) -->
        @if((auth()->user()->role === 'academic_admin') && isset($attendanceToday))
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Attendance Today</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $attendanceToday }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Present students</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- System Status (Technical, Super Admin) -->
        @if((auth()->user()->role === 'technical_admin' || auth()->user()->isSuperAdmin()) && isset($systemStatus))
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">System Status</p>
                    <p class="text-xl font-bold text-green-600 mt-2 capitalize">{{ $systemStatus }}</p>
                    @if($lastBackup)
                        <p class="text-xs text-gray-500 mt-1">Last backup: {{ $lastBackup->diffForHumans() }}</p>
                    @else
                        <p class="text-xs text-red-500 mt-1">No backups found</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Action Required & Quick Actions (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Action Required Section -->
            @if(isset($actionItems) && count($actionItems) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Action Required
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Pending Grade Approvals -->
                    @if(isset($actionItems['grades']) && $actionItems['grades']->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Grades Awaiting Approval ({{ $actionItems['grades']->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($actionItems['grades']->take(3) as $grade)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $grade->student->full_name ?? 'Unnamed Student' }}</p>
                                    <p class="text-xs text-gray-600">{{ $grade->classSchedule->subject->name }} - Quarter {{ $grade->quarter }}</p>
                                </div>
                                <a href="{{ route('admin.grade-approval.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Review →</a>
                            </div>
                            @endforeach
                        </div>
                        @if($actionItems['grades']->count() > 3)
                        <a href="{{ route('admin.grade-approval.index') }}" class="block text-center text-sm text-green-600 hover:text-green-700 font-medium mt-3">
                            View all {{ $actionItems['grades']->count() }} pending grades →
                        </a>
                        @endif
                    </div>
                    @endif

                    <!-- Unassigned Classes -->
                    @if(isset($actionItems['unassigned']) && $actionItems['unassigned']->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Unassigned Classes ({{ $actionItems['unassigned']->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($actionItems['unassigned']->take(3) as $schedule)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $schedule->subject->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $schedule->section->name }}</p>
                                </div>
                                <a href="{{ route('admin.class-schedules.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Assign →</a>
                            </div>
                            @endforeach
                        </div>
                        @if($actionItems['unassigned']->count() > 3)
                        <a href="{{ route('admin.class-schedules.index') }}" class="block text-center text-sm text-green-600 hover:text-green-700 font-medium mt-3">
                            View all {{ $actionItems['unassigned']->count() }} unassigned classes →
                        </a>
                        @endif
                    </div>
                    @endif

                    @if((!isset($actionItems['grades']) || $actionItems['grades']->count() === 0) &&
                        (!isset($actionItems['unassigned']) || $actionItems['unassigned']->count() === 0))
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">No pending actions. Everything's up to date!</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Quick Actions
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Registrar Actions -->
                        @if(auth()->user()->role === 'registrar_admin' || auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.students.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-600 transition">
                                <svg class="w-5 h-5 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Enroll Student</h3>
                                <p class="text-xs text-gray-600">Add new student</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.grade-approval.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-600 transition">
                                <svg class="w-5 h-5 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Approve Grades</h3>
                                <p class="text-xs text-gray-600">Review submissions</p>
                            </div>
                        </a>
                        @endif

                        <!-- Academic Actions -->
                        @if(auth()->user()->role === 'academic_admin' || auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.teachers.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600 transition">
                                <svg class="w-5 h-5 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Add Teacher</h3>
                                <p class="text-xs text-gray-600">Create account</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.subjects.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-600 transition">
                                <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Manage Subjects</h3>
                                <p class="text-xs text-gray-600">Configure curriculum</p>
                            </div>
                        </a>
                        @endif

                        <!-- Technical Actions -->
                        @if(auth()->user()->role === 'technical_admin' || auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.backups.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-teal-600 transition">
                                <svg class="w-5 h-5 text-teal-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Backup Database</h3>
                                <p class="text-xs text-gray-600">Create backup</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.logs.activity') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-600 transition">
                                <svg class="w-5 h-5 text-orange-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Activity Logs</h3>
                                <p class="text-xs text-gray-600">View audit trail</p>
                            </div>
                        </a>
                        @endif

                        <!-- Super Admin - All Access -->
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.announcements.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-yellow-600 transition">
                                <svg class="w-5 h-5 text-yellow-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">New Announcement</h3>
                                <p class="text-xs text-gray-600">Post system notice</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Analytics Section -->
            @if(isset($enrollmentByStrand) && $enrollmentByStrand->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Enrollment by Strand
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($enrollmentByStrand as $strand)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $strand->name }} ({{ $strand->code }})</span>
                                <span class="text-sm font-bold text-gray-900">{{ $strand->student_profiles_count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $enrollmentByStrand->max('student_profiles_count') > 0 ? ($strand->student_profiles_count / $enrollmentByStrand->max('student_profiles_count')) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Announcements (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcements
                    </h2>
                </div>
                <div class="p-6">
                    @if($announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                            <div class="border-l-4 border-green-500 pl-4 py-2">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $announcement->title }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $announcement->published_at->diffForHumans() }}</span>
                                    <button onclick="showAnnouncementModal({{ $announcement->id }})" class="text-xs text-green-600 hover:text-green-700 font-medium">See more →</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.announcements.index') }}" class="block text-center text-sm text-green-600 hover:text-green-700 font-medium mt-4 pt-4 border-t border-gray-100">
                            View all announcements →
                        </a>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No announcements yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div id="announcementModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
            <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span id="modalDate"></span>
            </div>
            <div id="modalContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap"></div>
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeAnnouncementModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                Close
            </button>
        </div>
    </div>
</div>

<script>
const announcements = @json($announcements);

function showAnnouncementModal(announcementId) {
    const announcement = announcements.find(a => a.id === announcementId);
    if (announcement) {
        document.getElementById('modalTitle').textContent = announcement.title;
        document.getElementById('modalContent').textContent = announcement.content;
        document.getElementById('modalDate').textContent = new Date(announcement.published_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('announcementModal').classList.remove('hidden');
    }
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('announcementModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAnnouncementModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAnnouncementModal();
    }
});
</script>
@endsection
