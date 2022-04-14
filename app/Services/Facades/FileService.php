<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\FileService as ServicesFileService;

/**
 * @method static \App\Services\FileService setDriver(string $driver)
 * @method static \App\Services\FileService save(string $filePath, string $contents, array|string $options = [])
 * @method static \App\Services\FileService get(string $file)
 * @method static \Symfony\Component\HttpFoundation\StreamedResponse download(string $path, string $name = null, array $headers = [])
 * @method static \App\Services\FileService streamFile(string $path, \Illuminate\Http\File|\Illuminate\Http\UploadedFile|string $file, string $name, array $options = [])
 * @method static \App\Services\FileService prepend(string $path, string $data)
 * @method static \App\Services\FileService append(string $path, string $data)
 * @method static \App\Services\FileService copy(string $fromPath, string $toPath)
 * @method static \App\Services\FileService move(string $fromPath, string $toPath)
 * @method static \App\Services\FileService delete(string|array $paths)
 * @method static string upload(string $fieldKey, string $storePath, string $name = '', array|string $options = [])
 * @method static array getFiles(string $directory, bool $withSubDirectories = true)
 * @method static array getDirectories(string $directory, bool $withSubDirectories = false)
 * @method static \App\Services\FileService makeDirectory( string $directory)
 * @method static \App\Services\FileService mkdir(string $directory)
 * @method static \App\Services\FileService deleteDirectory(string $directory)
 * @method static string getUrl(string $path)
 * @method static string getTemporaryUrl(string $path, \DateTimeInterface|null $expiration = null, array $options = [])
 * @method static array getMetaData(string $path)
 *
 * @see \App\Services\FileService
 */
class FileService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return ServicesFileService::class;
    }
}
