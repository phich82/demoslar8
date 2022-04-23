<?php

namespace App\Models;

use App\Traits\GetTableNameModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screen extends Model
{
    use HasFactory, SoftDeletes, GetTableNameModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'screens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'route',
        'parent',
        'description',
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
