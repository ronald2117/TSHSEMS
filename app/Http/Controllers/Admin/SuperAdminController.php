<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'global_settings' => [
                'title' => 'Global System Settings',
                'description' => 'Super Admin exclusive system-wide configuration and controls',
                'icon' => 'shield-check',
                'color' => 'red',
                'links' => [
                    ['name' => 'System Settings', 'route' => 'admin.settings.index', 'icon' => 'cog', 'description' => 'Configure system-wide settings and preferences'],
                    ['name' => 'Feature Toggles', 'route' => 'admin.features.index', 'icon' => 'adjustments', 'description' => 'Enable or disable system features'],
                    ['name' => 'Maintenance Mode', 'route' => 'admin.maintenance.index', 'icon' => 'exclamation', 'description' => 'Control system maintenance mode'],
                    ['name' => 'Academic Year Locking', 'route' => 'admin.year-locking.index', 'icon' => 'lock-closed', 'description' => 'Lock/unlock academic years globally'],
                ],
            ],
            'security_audit' => [
                'title' => 'Security & Audit',
                'description' => 'Monitoring, security logs, and compliance audit trails',
                'icon' => 'shield-check',
                'color' => 'orange',
                'links' => [
                    ['name' => 'Activity Logs', 'route' => 'admin.audit.activity', 'icon' => 'clipboard-list', 'description' => 'System-wide activity and security audit log'],
                    ['name' => 'Login Logs', 'route' => 'admin.audit.login', 'icon' => 'shield-check', 'description' => 'Authentication attempts and login monitoring'],
                    ['name' => 'Grade Audit Logs', 'route' => 'admin.audit.grades', 'icon' => 'chart-bar', 'description' => 'Track all grade modifications for compliance'],
                ],
            ],
        ];

        return view('admin.super-admin.all-navigations', compact('navigationGroups'));
    }

    /**
     * Display system settings page
     */
    public function systemSettings()
    {
        $settings = [
            'school_name' => \App\Models\SystemSetting::get('school_name', 'Taysan Senior High School'),
            'school_address' => \App\Models\SystemSetting::get('school_address', 'Taysan, Batangas'),
            'school_contact' => \App\Models\SystemSetting::get('school_contact', ''),
            'school_email' => \App\Models\SystemSetting::get('school_email', ''),
            'grading_system' => \App\Models\SystemSetting::get('grading_system', 'deped_standard'),
            'session_timeout' => \App\Models\SystemSetting::get('session_timeout', 30),
            'allow_student_registration' => \App\Models\SystemSetting::get('allow_student_registration', false),
            'require_email_verification' => \App\Models\SystemSetting::get('require_email_verification', true),
        ];
        
        return view('admin.super-admin.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSystemSettings(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:500',
            'school_contact' => 'nullable|string|max:50',
            'school_email' => 'nullable|email|max:255',
            'grading_system' => 'required|in:deped_standard,custom',
            'session_timeout' => 'required|integer|min:5|max:120',
            'allow_student_registration' => 'boolean',
            'require_email_verification' => 'boolean',
        ]);
        
        // Save each setting
        foreach ($validated as $key => $value) {
            \App\Models\SystemSetting::set($key, $value);
        }
        
        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'system_settings_updated',
            'description' => 'Updated system settings',
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('admin.settings.index')->with('success', 'System settings updated successfully');
    }

    /**
     * Display feature toggles page
     */
    public function featureToggles()
    {
        $features = [
            [
                'key' => 'feature_attendance_tracking',
                'name' => 'Attendance Tracking',
                'description' => 'Enable daily attendance recording for teachers',
                'enabled' => \App\Models\SystemSetting::get('feature_attendance_tracking', true),
            ],
            [
                'key' => 'feature_document_requests',
                'name' => 'Document Requests',
                'description' => 'Allow students to request documents online',
                'enabled' => \App\Models\SystemSetting::get('feature_document_requests', true),
            ],
            [
                'key' => 'feature_grade_publishing',
                'name' => 'Grade Publishing',
                'description' => 'Allow teachers to submit grades for approval',
                'enabled' => \App\Models\SystemSetting::get('feature_grade_publishing', true),
            ],
            [
                'key' => 'feature_announcements',
                'name' => 'Announcements System',
                'description' => 'Enable announcement posting and viewing',
                'enabled' => \App\Models\SystemSetting::get('feature_announcements', true),
            ],
            [
                'key' => 'feature_grade_reports',
                'name' => 'Grade Reports',
                'description' => 'Enable Form 137/138 generation',
                'enabled' => \App\Models\SystemSetting::get('feature_grade_reports', true),
            ],
            [
                'key' => 'feature_bulk_import',
                'name' => 'Bulk Import',
                'description' => 'Allow bulk import of students via CSV/Excel',
                'enabled' => \App\Models\SystemSetting::get('feature_bulk_import', true),
            ],
        ];
        
        return view('admin.super-admin.features.index', compact('features'));
    }

    /**
     * Toggle a feature
     */
    public function toggleFeature(Request $request)
    {
        $validated = $request->validate([
            'feature_key' => 'required|string',
        ]);
        
        $featureKey = $validated['feature_key'];
        $currentValue = \App\Models\SystemSetting::get($featureKey, true);
        $newValue = !$currentValue;
        
        \App\Models\SystemSetting::set($featureKey, $newValue);
        
        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'feature_toggled',
            'description' => ($newValue ? 'Enabled' : 'Disabled') . " feature: {$featureKey}",
            'ip_address' => $request->ip(),
        ]);
        
        $featureName = str_replace(['feature_', '_'], ['', ' '], $featureKey);
        $status = $newValue ? 'enabled' : 'disabled';
        
        return back()->with('success', ucfirst($featureName) . " has been {$status}");
    }

    /**
     * Display maintenance mode page
     */
    public function maintenanceMode()
    {
        $maintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
        $maintenanceMessage = \App\Models\SystemSetting::getMaintenanceMessage();
        $allowSuperAdmin = \App\Models\SystemSetting::allowSuperAdminDuringMaintenance();
        
        return view('admin.super-admin.maintenance.index', compact(
            'maintenanceMode',
            'maintenanceMessage',
            'allowSuperAdmin'
        ));
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(Request $request)
    {
        $currentMode = \App\Models\SystemSetting::isMaintenanceMode();
        
        // Toggle maintenance mode
        \App\Models\SystemSetting::set('maintenance_mode', !$currentMode);
        
        // Update message and settings if provided
        if ($request->has('maintenance_message')) {
            \App\Models\SystemSetting::set('maintenance_message', $request->maintenance_message);
        }
        
        if ($request->has('allow_super_admin')) {
            \App\Models\SystemSetting::set('maintenance_allow_super_admin', $request->boolean('allow_super_admin'));
        }
        
        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $currentMode ? 'disabled_maintenance_mode' : 'enabled_maintenance_mode',
            'description' => ($currentMode ? 'Disabled' : 'Enabled') . ' system maintenance mode',
            'ip_address' => $request->ip(),
        ]);
        
        $message = $currentMode 
            ? 'Maintenance mode disabled. System is now accessible to all users.' 
            : 'Maintenance mode enabled. Only authorized admins can access the system.';
            
        return back()->with('success', $message);
    }

    /**
     * Display academic year locking page
     */
    public function yearLocking()
    {
        $schoolYears = \App\Models\SchoolYear::orderBy('start_date', 'desc')->get();
        return view('admin.super-admin.year-locking.index', compact('schoolYears'));
    }

    /**
     * Toggle academic year lock
     */
    public function toggleYearLock(Request $request, $id)
    {
        $schoolYear = \App\Models\SchoolYear::findOrFail($id);
        
        // Check if is_locked column exists, otherwise use a setting
        $lockKey = "school_year_{$id}_locked";
        $currentlyLocked = \App\Models\SystemSetting::get($lockKey, false);
        $newLockedState = !$currentlyLocked;
        
        \App\Models\SystemSetting::set($lockKey, $newLockedState);
        
        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $newLockedState ? 'school_year_locked' : 'school_year_unlocked',
            'description' => ($newLockedState ? 'Locked' : 'Unlocked') . " school year: {$schoolYear->name}",
            'ip_address' => $request->ip(),
        ]);
        
        $status = $newLockedState ? 'locked' : 'unlocked';
        return back()->with('success', "School year {$schoolYear->name} has been {$status}. " . 
            ($newLockedState ? 'Grade modifications are now restricted.' : 'Grade modifications are now allowed.'));
    }

    /**
     * Display activity logs page
     */
    public function activityLogs()
    {
        $logs = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.super-admin.audit.activity-logs', compact('logs'));
    }

    /**
     * Display login logs page
     */
    public function loginLogs()
    {
        $logs = \App\Models\LoginLog::with('user')
            ->orderBy('logged_in_at', 'desc')
            ->paginate(50);
        
        $stats = [
            'total' => \App\Models\LoginLog::count(),
            'successful' => \App\Models\LoginLog::successful()->count(),
            'failed' => \App\Models\LoginLog::failed()->count(),
            'today' => \App\Models\LoginLog::whereDate('logged_in_at', today())->count(),
        ];
        
        return view('admin.super-admin.audit.login-logs', compact('logs', 'stats'));
    }

    /**
     * Display grade audit logs page
     */
    public function gradeAuditLogs()
    {
        $logs = \App\Models\GradeAuditLog::with(['quarterlyGrade.student.studentProfile', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.super-admin.audit.grade-audit-logs', compact('logs'));
    }
}
