<?php

namespace Core\Storage\StorageDriver;

use Core\Environment\Environment;
use Core\Storage\File;
use Core\Storage\StorageDriver;

class LocalDriver implements StorageDriver {

    const LOCAL_STORAGE_DIRECTORY = '/web';

    private $urlDir;
    private $storageDir;

    public function __construct(string $path) {
        $this->urlDir = Environment::getConfig('storage.drivers.Local.basePath') . $path . '/';
        $this->baseDir = Environment::getDir(self::LOCAL_STORAGE_DIRECTORY . $this->urlDir);
    }

    public function save(File $file, string $fileName): bool {
        $storePath = $this->baseDir . $fileName;
        return move_uploaded_file($file->path(), $storePath);
    }

    public function url(string $fileName): string {
        return $this->urlDir . $fileName;
    }
}
