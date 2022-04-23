<?php

namespace App\Models;

use App\Models\RoleUser;
use App\Traits\GetTableNameModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes, GetTableNameModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'permissions',
        'track_log',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Customize 'created_at' and 'updated_at' columns
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // JSON column
        'permissions' => 'object',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, RoleUser::table(), RoleUser::$foreignKeyRole, RoleUser::$foreignKeyUser);
    }

    // public function isUserLogin()
    // {
    //     if (!auth()->check()) {
    //         return false;
    //     }
    //     // Get all roles of logged in user
    //     $rolesUser = auth()->user()->roles()->get()->map(function ($row) {
    //         return $row->pivot->role_id;
    //     })->toArray();
    //     // Check whether it is logged in user
    //     return count($this->newQuery()->whereIn('id', $rolesUser)->get()) > 0;
    // }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        //
    ];

    /**
     * @Global Scope
     *
     * The "booted" method of the model.
     *
     * - Apply query scopes to model
     *
     * @return void
     */
    protected static function booted()
    {
        // static::addGlobalScope(new NewScope);
        // static::addGlobalScope('ancient', function (Builder $builder) {
        //     $builder->where('created_at', '<', now()->subYears(2000));
        // });
    }

    /**
     * @Local Scope
     *
     * Scope a query to only include active users.
     *
     * @Uasge Role::active()->where('id', 1)->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    // public function scopeActive($query)
    // {
    //     $query->where('active', 1);
    // }

    /**
     * @Dynamic Scope
     *
     * Scope a query to only include users of a given type.
     *
     * @Uasge Role::ofType('admin')->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeOfType($query, $type)
    // {
    //     return $query->where('type', $type);
    // }
}
