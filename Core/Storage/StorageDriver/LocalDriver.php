<?php

namespace Core\Storage\StorageDriver;

use Core\Environment\Environment;
use Core\Storage\File;
use Core\Storage\StorageDriver;

class LocalDriver implements StorageDriver {

    const LOCAL_STORAGE_DIRECTORY = '/web';

    private $urlDir; // the public directory path to access the storage directory
    private $baseDir; // the private directory path to access the storage directory

    public function __construct(string $path) {
        $this->urlDir = Environment::getConfig('storage.drivers.Local.basePath') . $path . '/';
        $this->baseDir = Environment::getDir(self::LOCAL_STORAGE_DIRECTORY . $this->urlDir);
        if (!is_dir($this->baseDir)) mkdir($this->baseDir, 0777, true);
    }

    public function list(): array {
        return $this->scanDirectory('');
    }

    private function scanDirectory(string $directoryPath): array {
        $scannedList = array_diff(scandir($this->baseDir . $directoryPath), array('..', '.'));
        $fileList = [];
        foreach ($scannedList as $scannedListItem) {
            if (is_dir($scannedListItem)) {
                array_merge($fileList, $this->scanDirectory($this->baseDir . $directoryPath . $scannedListItem));
            } else {
                $fileList[] = $directoryPath . $scannedListItem;
            }
        }
        return $fileList;
    }

    public function save(File $file, string $fileName): bool {
        $storePath = $this->baseDir . $fileName;
        return move_uploaded_file($file->path(), $storePath);
    }

    public function url(string $fileName): string {
        return $this->urlDir . $fileName;
    }
}
