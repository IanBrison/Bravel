<?php

namespace Core\Storage;

class File {

    private $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function path(): string {
        return $this->path;
    }
}
