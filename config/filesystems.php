<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            // Specify permissions when saving the file or folder
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ],
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),

            // Other Disk Options...
            // 'cache' => [
            //     'store' => 'memcached',
            //     'expire' => 600,
            //     'prefix' => 'cache-prefix',
            // ],
        ],

        // New driver (FTPFileSystemServiceProvider.php)
        'ftp' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),

            // Optional FTP Settings...
            // 'port' => env('FTP_PORT', 21),
            // 'root' => env('FTP_ROOT'),
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
            // 'ignorePassiveAddress' => false,
        ],

        // New driver (SFTPFileSystemServiceProvider.php)
        'sftp' => [
            'driver' => 'sftp',
            'host' => env('SFTP_HOST'),

            // Settings for basic authentication...
            'username' => env('SFTP_USERNAME'),
            'password' => env('SFTP_PASSWORD'),

            // Settings for SSH key based authentication with encryption password...
            'privateKey' => env('SFTP_PRIVATE_KEY'),
            'password' => env('SFTP_PASSWORD'),

            // Optional SFTP Settings...
            // 'port' => env('SFTP_PORT', 22),
            // 'root' => env('SFTP_ROOT'),
            // 'timeout' => 30,
        ],

        // New driver (DropboxFileSystemServiceProvider.php)
        'dropbox' => [
            'driver' => 'dropbox',
            'root' => storage_path('app'),
            'authorization_token' => env('DROPBOX_AUTHORIZATION_TOKEN'),
            'expire' => 600,
        ],

        // New driver (StashFileSystemServiceProvider.php)
        'stash' => [
            'driver' => 'stash',
            'root' => storage_path('app'),
            'key' =>  env('STASH_KEY', 'flysystem'),
            'expire' => env('STASH_EXPIRE', null),
        ],

        // New driver (PredisFileSystemServiceProvider.php)
        'predis' => [
            'driver' => 'predis',
            'root' => storage_path('app'),
        ],

        // New driver (MemcachedFileSystemServiceProvider.php)
        'memcached' => [
            'driver' => 'memcached',
            'root' => storage_path('app'),
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'host' => env('MEMCACHED_HOST', '127.0.0.1'),
            'port' => env('MEMCACHED_PORT', 11211),
            'username' => env('MEMCACHED_USERNAME'),
            'password' => env('MEMCACHED_PASSWORD'),
            'timeout' => 2000,
            'weight' => 100,
            'key' => env('MEMCACHED_KEY', 'flysystem'),
            'expire' => env('MEMCACHED_EXPIRE', null),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
