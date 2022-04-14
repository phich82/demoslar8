<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

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
        //
    ];

    /**
     * Customize 'created_at' and 'updated_at' columns
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

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
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
