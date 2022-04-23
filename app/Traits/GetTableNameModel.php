<?php

namespace App\Traits;

trait GetTableNameModel
{
    /**
     * Get table name
     *
     * @return string
     */
    public static function table()
    {
        return (new static)->getTable();
    }

    /**
     * Get primary key
     *
     * @return string
     */
    public static function primaryKey()
    {
        return (new static)->getKeyName();
    }
}
