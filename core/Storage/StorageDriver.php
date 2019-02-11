<?php

namespace Core\Storage;

interface StorageDriver {

    public function save(File $file, string $fileName): bool;
}
