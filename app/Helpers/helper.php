<?php

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return request()->is('admin/*');
    }
}

if (!function_exists('isApi')) {
    function isApi()
    {
        return request()->is('api/*');
    }
}

if (!function_exists('read')) {
    /**
     * Get an item from an array using "dot" notation after reading the file content.
     *
     * @param string $path [
     *  - Format: {path.to.file}:{key_1.key_2.key_n}
     *    + path_to_file: path separated with dot without extension (example: app.Http.Controllers)
     *    + `:` mark: optional. It is used to separate file path and keys
     *    + keys: The keys are separated together by `dot` mark
     * ]
     * @param mixed $default
     * @return mixed
     */
    function read($path, $default = null)
    {
        try {
            // Extract filename, keys and build the full path of file
            $splits = explode(':', $path);
            $path = $splits[0];
            $path = implode('/', explode('.', $path)).'.php';

            // Get content of file
            $contents = include(base_path($path));

            if (!is_array($contents)) {
                return $default;
            }

            // Extract keys
            $keys = '';
            if (count($splits) > 1) {
                $keys = trim($splits[1]);
            }

            // If keys empty, get all
            if (empty($keys)) {
                return $contents;
            }

            // Get values by keys
            return \Illuminate\Support\Arr::get($contents, $keys, $default);
        } catch (Exception $e) {
            return $default;
        }
    }
}
