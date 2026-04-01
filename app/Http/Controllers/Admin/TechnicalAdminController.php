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

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('admin.technical-admin.logs.activity', compact('logs'));
    }

    /**
     * Display grade audit logs
     */
    public function gradeAuditLogs(Request $request)
    {
        $query = GradeAuditLog::with(['quarterlyGrade.student.studentProfile', 'user'])
            ->latest();

        // Search filter - student name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('quarterlyGrade.student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function ($profileQuery) use ($search) {
                      $profileQuery->where('student_id', 'like', "%{$search}%");
                  });
            });
        }

        // Changed by filter - user who made the change
        if ($request->filled('changed_by')) {
            $changedBy = $request->changed_by;
            $query->whereHas('user', function ($q) use ($changedBy) {
                $q->where('first_name', 'like', "%{$changedBy}%")
                  ->orWhere('last_name', 'like', "%{$changedBy}%")
                  ->orWhere('middle_name', 'like', "%{$changedBy}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Legacy student_id filter (keep for backward compatibility)
        if ($request->filled('student_id')) {
            $query->whereHas('quarterlyGrade', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('admin.technical-admin.logs.grades', compact('logs'));
    }

    /**
     * Display login logs
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
        
        return view('admin.technical-admin.logs.login', compact('logs', 'stats'));
    }

    /**
     * Display database backups list
     */
    public function backupsIndex()
    {
        $backupDir = storage_path('app/backups');

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $files = array_merge(
            glob($backupDir . '/*.sql') ?: [],
            glob($backupDir . '/*.sqlite') ?: []
        );

        $backups = collect($files)
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'date' => filemtime($file),
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
            $filename = 'backup-' . now()->format('Y-m-d-His');
            $backupDir = storage_path('app/backups');

            // Ensure backups directory exists
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $driver = config('database.default');

            if ($driver === 'sqlite') {
                // SQLite backup: Simply copy the database file
                $dbPath = config('database.connections.sqlite.database');
                
                if (!file_exists($dbPath)) {
                    return back()->withErrors(['error' => 'Database file not found at: ' . $dbPath]);
                }

                $backupPath = $backupDir . '/' . $filename . '.sqlite';
                
                if (copy($dbPath, $backupPath)) {
                    // Log the activity
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'database_backup',
                        'description' => 'Created database backup: ' . basename($backupPath),
                        'ip_address' => request()->ip(),
                    ]);

                    return back()->with('success', 'Database backup created successfully');
                } else {
                    return back()->withErrors(['error' => 'Failed to copy database file']);
                }

            } elseif ($driver === 'mysql' || $driver === 'mariadb') {
                // MySQL/MariaDB backup: Use mysqldump
                $filename .= '.sql';
                $path = $backupDir . '/' . $filename;

                $dbHost = config("database.connections.{$driver}.host");
                $dbName = config("database.connections.{$driver}.database");
                $dbUser = config("database.connections.{$driver}.username");
                $dbPass = config("database.connections.{$driver}.password");
                $dbPort = config("database.connections.{$driver}.port", '3306');

                // Try to find mysqldump in common locations
                $mysqldumpPaths = [
                    'mysqldump', // In PATH
                    'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
                    'C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqldump.exe',
                    'C:\xampp\mysql\bin\mysqldump.exe',
                    'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe',
                    'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
                ];

                $mysqldump = null;
                foreach ($mysqldumpPaths as $testPath) {
                    exec("where.exe \"$testPath\" 2>nul", $testOutput, $testReturn);
                    if ($testReturn === 0 || (strpos($testPath, ':') !== false && file_exists($testPath))) {
                        $mysqldump = $testPath;
                        break;
                    }
                }

                if (!$mysqldump) {
                    return back()->withErrors(['error' => 'mysqldump not found. Please add MySQL bin directory to your PATH environment variable. Typical location: C:\Program Files\MySQL\MySQL Server 8.0\bin']);
                }

                // Build mysqldump command for Windows
                $passwordPart = $dbPass ? '--password="' . addslashes($dbPass) . '"' : '';
                
                $command = sprintf(
                    '"%s" --host=%s --port=%s --user=%s %s --no-tablespaces --skip-lock-tables %s > "%s" 2>&1',
                    $mysqldump,
                    escapeshellarg($dbHost),
                    escapeshellarg($dbPort),
                    escapeshellarg($dbUser),
                    $passwordPart,
                    escapeshellarg($dbName),
                    $path
                );

                exec($command, $output, $returnVar);

                if ($returnVar === 0 && file_exists($path) && filesize($path) > 0) {
                    // Log the activity
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'database_backup',
                        'description' => 'Created database backup: ' . $filename,
                        'ip_address' => request()->ip(),
                    ]);

                    return back()->with('success', 'Database backup created successfully');
                } else {
                    $errorMsg = 'Failed to create database backup.';
                    if (!empty($output)) {
                        $errorMsg .= ' Error: ' . implode(' ', $output);
                    }
                    return back()->withErrors(['error' => $errorMsg]);
                }

            } else {
                return back()->withErrors(['error' => 'Unsupported database driver: ' . $driver]);
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
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            return response()->download($path);
        }

        return back()->withErrors(['error' => 'Backup file not found']);
    }

    /**
     * Delete a backup file
     */
    public function deleteBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);

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
        $driver = config('database.default');
        
        if ($driver === 'sqlite') {
            // For SQLite, get the file size directly
            $dbPath = config('database.connections.sqlite.database');
            return file_exists($dbPath) ? filesize($dbPath) : 0;
        } elseif ($driver === 'mysql') {
            // For MySQL, query information_schema
            $dbName = config('database.connections.mysql.database');
            
            $result = DB::select(
                "SELECT SUM(data_length + index_length) as size 
                 FROM information_schema.TABLES 
                 WHERE table_schema = ?",
                [$dbName]
            );

            return $result[0]->size ?? 0;
        }
        
        return 0;
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
