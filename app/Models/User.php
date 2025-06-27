<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'role',     // just a label, not used for checks
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* ─── Relationships ─── */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
