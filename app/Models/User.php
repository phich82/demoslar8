<?php

namespace App\Models;

use stdClass;
use App\Services\Core;
use App\Traits\GetTableNameModel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, GetTableNameModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'access_token',
        'track_log',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check whether user role is `admin` role
     *
     * @return bool
     */
    public function isAdmin()
    {
        if (!auth()->check()) {
            return false;
        }

        foreach ($this->roles()->get() as $role) {
            if ($role->code === 'admin') {
                return true;
            }
        }

        return false;
    }

    /**
     * Get permission
     *
     * @return object
     */
    public function permission()
    {
        return Core::permission($this->roles()->get());
    }

    /**
     * Get first role that belong to the user.
     *
     * @return mixed
     */
    public function role()
    {
        return $this->roles()->first();
    }

    /**
     * The roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleUser::table(), RoleUser::$foreignKeyUser, RoleUser::$foreignKeyRole);
    }
}
