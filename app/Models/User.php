<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'username', 'email', 'password',
        'first_name', 'last_name', 'role',
        'permissions', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'permissions'       => 'array',
        'email_verified_at' => 'datetime',
    ];

    /* ────────────── Relationships ────────────── */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
