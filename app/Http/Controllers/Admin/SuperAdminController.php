<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SuperAdminController extends Controller
{
    /**
     * Display unified admin navigation page
     * Provides super admin access to all admin navigation options
     */
    public function allAdminNavigations()
    {
        // Navigation groups for different admin types
        $navigationGroups = [
            'academic' => [
                'title' => 'Academic Administration',
                'description' => 'Manage academic structure, teachers, and curriculum',
                'icon' => 'academic-cap',
                'color' => 'blue',
                'links' => [
                    ['name' => 'Students', 'route' => 'admin.students.index', 'icon' => 'academic-cap', 'description' => 'Manage student records and profiles'],
                    ['name' => 'Teachers', 'route' => 'admin.teachers.index', 'icon' => 'briefcase', 'description' => 'Manage teacher accounts and profiles'],
                    ['name' => 'School Years', 'route' => 'admin.school-years.index', 'icon' => 'calendar', 'description' => 'Manage academic school years'],
                    ['name' => 'Academic Periods', 'route' => 'admin.academic-periods.index', 'icon' => 'calendar', 'description' => 'Manage semesters and quarters'],
                    ['name' => 'Tracks', 'route' => 'admin.tracks.index', 'icon' => 'book-open', 'description' => 'Manage academic tracks'],
                    ['name' => 'Strands', 'route' => 'admin.strands.index', 'icon' => 'book-open', 'description' => 'Manage strands within tracks'],
                    ['name' => 'Sections', 'route' => 'admin.sections.index', 'icon' => 'building', 'description' => 'Manage class sections'],
                    ['name' => 'Subjects', 'route' => 'admin.subjects.index', 'icon' => 'book-open', 'description' => 'Manage subjects and curriculum'],
                    ['name' => 'Class Schedules', 'route' => 'admin.class-schedules.index', 'icon' => 'calendar', 'description' => 'Manage class schedules and assignments'],
                    ['name' => 'Announcements', 'route' => 'admin.announcements.index', 'icon' => 'bell', 'description' => 'Post and manage announcements'],
                ],
            ],
            'registrar' => [
                'title' => 'Registrar Administration',
                'description' => 'Manage enrollment, grades, and official documents',
                'icon' => 'clipboard-check',
                'color' => 'green',
                'links' => [
                    ['name' => 'Students', 'route' => 'admin.students.index', 'icon' => 'academic-cap', 'description' => 'View and manage student records'],
                    ['name' => 'Enrollment', 'route' => 'admin.enrollment.index', 'icon' => 'clipboard-check', 'description' => 'Process student enrollment'],
                    ['name' => 'Grade Approval', 'route' => 'admin.grade-approval.index', 'icon' => 'check-circle', 'description' => 'Review and approve quarterly grades'],
                    ['name' => 'Document Requests', 'route' => 'admin.documents.index', 'icon' => 'document', 'description' => 'Process student document requests'],
                    ['name' => 'Reports & Forms', 'route' => 'admin.reports.index', 'icon' => 'chart-bar', 'description' => 'Generate academic reports and forms'],
                ],
            ],
            'technical' => [
                'title' => 'Technical Administration',
                'description' => 'System maintenance, security, and monitoring',
                'icon' => 'cog',
                'color' => 'purple',
                'links' => [
                    ['name' => 'User Management', 'route' => 'admin.users.index', 'icon' => 'users', 'description' => 'Manage all user accounts'],
                    ['name' => 'Database Backups', 'route' => 'admin.backups.index', 'icon' => 'database', 'description' => 'Create and manage database backups'],
                    ['name' => 'Activity Logs', 'route' => 'admin.logs.activity', 'icon' => 'clipboard-list', 'description' => 'View system activity logs'],
                    ['name' => 'Grade Audit Logs', 'route' => 'admin.logs.grades', 'icon' => 'chart-bar', 'description' => 'View grade modification audit trail'],
                    ['name' => 'System Statistics', 'route' => 'admin.system.stats', 'icon' => 'chart-square-bar', 'description' => 'View system statistics and metrics'],
                ],
            ],
        ];

        return view('admin.super-admin.all-navigations', compact('navigationGroups'));
    }
}
