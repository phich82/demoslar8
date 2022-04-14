<?php

namespace App\Providers;

use League\Flysystem\Adapter\Local as DropboxLocalAdapter;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Adapter as DropboxStore;
use League\Flysystem\Cached\Storage\Stash as StashStore;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;
use League\Flysystem\Cached\Storage\Predis as PredisStore;
use League\Flysystem\Cached\Storage\Memcached as MemcachedStore;

class SFTPFileSystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Create new storage driver (sftp)
        Storage::extend('sftp', function ($app, $config) {
            // Value of $config variable is as config('filesystem.sftp)
            $adapter = new SftpAdapter($config);
            return new Filesystem($this->getCachedAdapter($adapter, $config));
        });
    }

    /**
     * Get cached adapter
     *
     * @important For using cache, must install a package (league/flysystem-cached-adapter)
     *
     * @param  mixed $adapter
     * @param  array $config
     * @return mixed
     */
    private function getCachedAdapter($adapter, $config = [])
    {
        $cacheStore = null;
        $cacheType = env('STORAGE_CACHE_TYPE', 'memory');
        $config = config("filesystems.{$cacheType}", $config);

        switch ($cacheType) {
            case 'memory':
                // Create the cache store
                $cacheStore = new MemoryStore();
                break;
            case 'predis':
                $args = [];
                $classPredisClient = 'Predis\Client';
                // Create the cache store
                if (class_exists($classPredisClient)) {
                    $client = new $classPredisClient();
                    $args[] = $client;
                }
                $cacheStore = new PredisStore(...$args);
                break;
            case 'memcached':
                $memcached = null;
                $classMemcached = 'Memcached';
                if (class_exists($classMemcached)) {
                    $memcached = new $classMemcached();
                    $memcached->addServer($config['host'], $config['port']);
                    // Create the cache store
                    $cacheStore = new MemcachedStore($memcached, $config['key'] ?? 'flysystem', $config['expire'] ?? null);
                }
                break;
            case 'stash':
                $classStashPool = 'Stash\Pool';
                if (class_exists($classStashPool)) {
                    // You can optionally pass a driver (recommended, default: in-memory driver)
                    $pool = new $classStashPool();
                    // Storage key and expire time are optional
                    $cacheStore = new StashStore($pool, $config['key'] ?? 'flysystem', $config['expire'] ?? null);
                }
                break;
            case 'dropbox':
                $local = new DropboxLocalAdapter($config['root']);
                $cacheStore = new DropboxStore($local, $config['root'] ?? storage_path('app'), $config['expire'] ?? null);
                break;
        }
        return $cacheStore ? new CachedAdapter($adapter, $cacheStore) : $adapter;
    }
}
