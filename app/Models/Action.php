<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Action extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
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
