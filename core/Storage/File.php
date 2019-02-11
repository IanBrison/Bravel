<?php

namespace Core\Storage;

class File {

    private $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public static function constructFromRequest(array $request) {
        return new self($request['tmp_name']);
    }

    public function path(): string {
        return $this->path;
    }
}
