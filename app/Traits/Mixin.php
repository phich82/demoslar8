<?php

namespace App\Traits;

use Exception;

/**
 * For creating a facade
 */
trait Mixin
{
    /**
     * __call
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$arguments);
        }
        throw new Exception("[Mixin] Method [$method] not exist.");
    }

    /**
     * __callStatic
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return (new static)->$method(...$arguments);
    }
}
