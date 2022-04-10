<?php

namespace App\Services;

class Session
{
    /**
     * Save session
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        session([$key => $value]);
    }

    /**
     * Get session
     *
     * @param  string $key
     * @param  mixed $defaultValue
     * @return mixed
     */
    public static function get($key, $defaultValue = null)
    {
        return session($key, $defaultValue);
    }

    /**
     * Get all sessions
     *
     * @return array
     */
    public static function all()
    {
        return session()->all();
    }

    /**
     * Filtering sessions by given keys
     *
     * @param  string|array $keys
     * @return array
     */
    public static function only($keys = [])
    {
        return array_reduce($keys, function ($carry, $key) {
            $carry[$key] = self::get($key);
            return $carry;
        }, []);
    }

    /**
     * Delete one or more sessions
     *
     * @param  string|array $keys
     * @return bool
     */
    public static function clear($keys = [])
    {
        if (!is_string($keys) && !is_array($keys)) {
            return false;
        }
        if (is_string($keys)) {
            $keys = [$keys];
        }
        // Delete all sessions
        if (empty($keys)) {
            session()->flush();
            return true;
        }
        // Delete sessions by given keys
        session()->forget($keys);
        return true;
    }

    /**
     * Regenerate session id
     *
     * @return bool
     */
    public static function regenerate()
    {
        return session()->regenerate();
    }

    /**
     * Clear all sessions and regenearte them
     *
     * @return bool
     */
    public static function invalidate()
    {
        return session()->invalidate();
    }

    /**
     * Store session for the next request
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function flash($key, $value = null)
    {
        return session()->flash($key, $value);
    }
}
