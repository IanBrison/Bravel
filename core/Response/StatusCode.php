<?php

namespace Core\Response;

class StatusCode {

    protected $code;
    protected $text;

    public function __construct(int $code, string $text = '') {
        $this->code = $code;
        $this->text = $text;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function getText(): string {
        return $this->text;
    }
}
