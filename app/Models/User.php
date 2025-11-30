<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Traits\HasRoles; // Uncomment this after installing Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // use HasRoles; // Uncomment this after installing Spatie

    /**
     * The attributes that are mass assignable.
     * These must match the columns in the migration above.
     */
    protected $fillable = [
        'login_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'avatar_path',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships can be added here later
    // public function studentProfile() { return $this->hasOne(StudentProfile::class); }
}
