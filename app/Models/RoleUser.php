<?php

namespace App\Models;

use App\Traits\GetTableNameModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Model
{
    use HasFactory, SoftDeletes, GetTableNameModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_users';

    public static $foreignKeyUser = 'user_id';
    public static $foreignKeyRole = 'role_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'user_id',
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
}
