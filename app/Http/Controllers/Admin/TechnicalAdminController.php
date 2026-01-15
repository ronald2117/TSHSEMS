<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GradeAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TechnicalAdminController extends Controller
{
    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        // Smart search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('admin.technical-admin.logs.activity', compact('logs'));
    }

    /**
     * Display grade audit logs
     */
    public function gradeAuditLogs(Request $request)
    {
        $query = GradeAuditLog::with(['quarterlyGrade.student.user', 'user'])
            ->latest();

        if ($request->has('student_id')) {
            $query->whereHas('quarterlyGrade', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        $logs = $query->paginate(50);

        return view('admin.technical-admin.logs.grades', compact('logs'));
    }

    /**
     * Display database backups list
     */
    public function backupsIndex()
    {
        $backups = collect(Storage::disk('local')->files('backups'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('admin.technical-admin.backups.index', compact('backups'));
    }

    /**
     * Create a new database backup
     */
    public function createBackup()
    {
        try {
            $filename = 'backup-' . now()->format('Y-m-d-His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Ensure backups directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Create backup using mysqldump
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                // Log the activity
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'database_backup',
                    'description' => 'Created database backup: ' . $filename,
                    'ip_address' => request()->ip(),
                ]);

                return back()->with('success', 'Database backup created successfully');
            } else {
                return back()->withErrors(['error' => 'Failed to create database backup']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Backup error: ' . $e->getMessage()]);
        }
    }

    /**
     * Download a backup file
     */
    public function downloadBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }

        return back()->withErrors(['error' => 'Backup file not found']);
    }

    /**
     * Delete a backup file
     */
    public function deleteBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'database_backup_deleted',
                'description' => 'Deleted database backup: ' . $filename,
                'ip_address' => request()->ip(),
            ]);

            return back()->with('success', 'Backup deleted successfully');
        }

        return back()->withErrors(['error' => 'Backup file not found']);
    }

    /**
     * Show password reset form
     */
    public function passwordResetForm($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        return view('admin.technical-admin.users.reset-password', compact('user'));
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $userId)
    {
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed',
            'reason' => 'required|string|max:255',
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'password_reset',
            'description' => "Reset password for {$user->name} (ID: {$user->id}). Reason: {$validated['reason']}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Password reset successfully');
    }

    /**
     * Display system statistics
     */
    public function systemStats()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_students' => \App\Models\StudentProfile::count(),
            'total_teachers' => \App\Models\TeacherProfile::count(),
            'database_size' => $this->getDatabaseSize(),
            'storage_used' => $this->getStorageUsed(),
            'recent_activities' => ActivityLog::with('user')->latest()->take(10)->get(),
        ];

        return view('admin.technical-admin.stats', compact('stats'));
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        $dbName = config('database.connections.mysql.database');
        
        $result = DB::select(
            "SELECT SUM(data_length + index_length) as size 
             FROM information_schema.TABLES 
             WHERE table_schema = ?",
            [$dbName]
        );

        return $result[0]->size ?? 0;
    }

    /**
     * Get storage used
     */
    private function getStorageUsed()
    {
        $size = 0;
        $path = storage_path('app');
        
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $size;
    }
}
