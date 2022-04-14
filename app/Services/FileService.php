<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $storage = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        if (!$this->driver) {
            $this->driver = env('FILESYSTEM_DRIVER', 'local');
        }
        $this->storage = Storage::disk($this->driver);
    }

    /**
     * Set file system driver
     *
     * @param  string $driver
     * @return \FileService
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Save
     *
     * @param  string $filePath
     * @param  string $contents
     * @param  array|string $options [string: file visibility (private, public)]
     * @return \FileService
     */
    public function save($filePath, $contents, $options = [])
    {
        $this->storage->put($filePath, $contents, $options);

        return $this;
    }

    /**
     * Get the file
     *
     * @param  string $file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($file)
    {
        return $this->storage->get($file);
    }

    /**
     * Download the file
     *
     * @param  string $path
     * @param  string $name
     * @param  array $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($path, $name = null, $headers = [])
    {
        return Storage::download($path, $name, $headers);
    }

    /**
     * Stream the file
     *
     * @param  string $path
     * @param  \Illuminate\Http\File|\Illuminate\Http\UploadedFile|string $file
     * @param  string $name
     * @param  array $options
     * @return \FileService
     */
    public function streamFile($path, $file, $name, $options = [])
    {
        Storage::putFileAs($path, $file, $name, $options);

        return $this;
    }

    /**
     * Prepend data to given file
     *
     * @param  string $path
     * @param  string $data
     * @return \FileService
     */
    public function prepend($path, $data)
    {
        Storage::prepend($path, $data);

        return $this;
    }

    /**
     * Append data to given file
     *
     * @param  string $path
     * @param  string $data
     * @return \FileService
     */
    public function append($path, $data)
    {
        Storage::append($path, $data);

        return $this;
    }

    /**
     * Copy file
     *
     * @param  string $fromPath
     * @param  string $toPath
     * @return \FileService
     */
    public function copy($fromPath, $toPath)
    {
        Storage::copy($fromPath, $toPath);

        return $this;
    }

    /**
     * Move file
     *
     * @param  string $fromPath
     * @param  string $toPath
     * @return \FileService
     */
    public function move($fromPath, $toPath)
    {
        Storage::move($fromPath, $toPath);

        return $this;
    }

    /**
     * Delete files
     *
     * @param  string|array $paths
     * @return \FileService
     */
    public function delete($paths)
    {
        if (is_string($paths)) {
            $paths = [$paths];
        }
        $this->storage->delete($paths);

        return $this;
    }

    /**
     * Upload a file
     *
     * @param  string $fieldKey
     * @param  string $storePath
     * @param  string $name [assigned to the stored file]
     * @param  array|string $options [string: specify a disk (s3)]
     * @return string [uploaded file path]
     */
    public function upload($fieldKey, $storePath, $name = '', $options = [])
    {
        // $path = request()->file($fieldKey)->store($storePath, $options);
        // $path = Storage::putFile($storePath, request()->file($fieldKey), $options);
        $path = request()->file($fieldKey)->storeAs($storePath, $name, $options);
        $path = Storage::putFileAs($storePath, request()->file($fieldKey), $name, $options);
        return $path;
    }

    /**
     * Get all files wihthin a directory (included sub-directories)
     *
     * @param  string $directory
     * @param  bool $withSubDirectories
     * @return array
     */
    public function getFiles($directory, $withSubDirectories = true)
    {
        return $withSubDirectories
            ? Storage::allFiles($directory)
            : Storage::files($directory);
    }

    /**
     * Get all folders wihthin a given directory (included sub-directories)
     *
     * @param  string $directory
     * @param  bool $withSubDirectories
     * @return array
     */
    public function getDirectories($directory, $withSubDirectories = false)
    {
        return $withSubDirectories
            ? Storage::allDirectories($directory)
            : Storage::directories($directory);
    }

    /**
     * Make a directory
     *
     * @param  string $directory
     * @return \FileService
     */
    public function makeDirectory($directory)
    {
        Storage::makeDirectory($directory);

        return $this;
    }

    /**
     * @alias makeDirectory
     *
     * Make a directory
     *
     * @param  string $directory
     * @return \FileService
     */
    public function mkdir($directory)
    {
        return $this->makeDirectory($directory);
    }

    /**
     * Delete a directory
     *
     * @param  string $directory
     * @return \FileService
     */
    public function deleteDirectory($directory)
    {
        Storage::deleteDirectory($directory);

        return $this;
    }

    /**
     * Get url by the given path
     *
     * @param  string $path
     * @return string
     */
    public function getUrl($path)
    {
        return Storage::url($path);
    }

    /**
     * Get temporary url by the given path
     *
     * @param  string $path
     * @param  \DateTimeInterface|null $expiration
     * @param  array $options
     * @return string
     */
    public function getTemporaryUrl($path, $expiration = null, $options = [])
    {
        return Storage::temporaryUrl($path, $expiration ?: now()->addMinutes(5), $options);
    }

    /**
     * get the file metadata
     *
     * @param  string $path
     * @return array
     */
    public static function getMetaData($path)
    {
        return [
            'path' => Storage::path($path),
            'size' => Storage::size($path),
            'last_modified' => Storage::lastModified($path),
        ];
    }

    /**
     * Verify a driver
     *
     * @param  string $driver
     * @return bool
     */
    private static function _verifyAllowedDrivers($driver)
    {
        return in_array($driver, [
            'local',
            'public',
            's3',
            'ftp',
            'sftp',
        ]);
    }
}
