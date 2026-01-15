<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $activities = [
            // Login activities
            ['action' => 'login', 'description' => 'User logged in: John Doe (Role: super_admin)', 'days_ago' => 0],
            ['action' => 'login', 'description' => 'User logged in: Jane Smith (Role: teacher)', 'days_ago' => 0],
            ['action' => 'login', 'description' => 'User logged in: Maria Garcia (Role: student)', 'days_ago' => 1],
            
            // Student management
            ['action' => 'create', 'description' => 'Created student: Pedro Santos (LRN: 123456789012)', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Updated student: Maria Garcia (LRN: 987654321098)', 'days_ago' => 2],
            ['action' => 'delete', 'description' => 'Deleted student: Old Student (LRN: 111222333444)', 'days_ago' => 3],
            
            // Teacher management
            ['action' => 'create', 'description' => 'Created teacher: Robert Johnson (ID: TCH-001)', 'days_ago' => 2],
            ['action' => 'update', 'description' => 'Updated teacher: Jane Smith', 'days_ago' => 3],
            
            // User management
            ['action' => 'create', 'description' => 'Created user: New Admin (Role: registrar_admin)', 'days_ago' => 3],
            ['action' => 'update', 'description' => 'Changed user status to inactive: Suspended User', 'days_ago' => 4],
            ['action' => 'delete', 'description' => 'Deleted user: Old Admin (Role: academic_admin)', 'days_ago' => 5],
            
            // Grade management
            ['action' => 'approve', 'description' => 'Approved grade for student: Maria Garcia - Mathematics (Quarter 1)', 'days_ago' => 1],
            ['action' => 'return', 'description' => 'Returned grade for student: Pedro Santos - English (Reason: Missing assessment)', 'days_ago' => 2],
            ['action' => 'override', 'description' => 'Overrode grade for student: Juan Cruz - Science (Old: 72 → New: 75)', 'days_ago' => 3],
            
            // Announcements
            ['action' => 'create', 'description' => 'Created announcement: First Quarter Exam Schedule', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Updated announcement: School Calendar 2025-2026', 'days_ago' => 2],
            ['action' => 'delete', 'description' => 'Deleted announcement: Old Event Notice', 'days_ago' => 4],
            
            // Academic periods
            ['action' => 'create', 'description' => 'Created academic period: 1st Semester', 'days_ago' => 7],
            ['action' => 'update', 'description' => 'Changed academic period status to Closed: 1st Quarter', 'days_ago' => 5],
            
            // System activities
            ['action' => 'backup', 'description' => 'Created database backup: backup-2026-01-15-083000.sql', 'days_ago' => 0],
            ['action' => 'password_reset', 'description' => 'Reset password for user: forgotten@example.com', 'days_ago' => 6],
            
            // Logout activities
            ['action' => 'logout', 'description' => 'User logged out: John Doe', 'days_ago' => 0],
            ['action' => 'logout', 'description' => 'User logged out: Jane Smith', 'days_ago' => 1],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create([
                'user_id' => $users->random()->id,
                'action' => $activity['action'],
                'description' => $activity['description'],
                'ip_address' => $this->getRandomIp(),
                'created_at' => now()->subDays($activity['days_ago'])->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);
        }

        $this->command->info('Activity logs seeded successfully!');
    }

    /**
     * Generate a random IP address for testing
     */
    private function getRandomIp(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }
}
