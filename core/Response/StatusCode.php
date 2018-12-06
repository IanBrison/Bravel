<?php

namespace Core\Response;

class StatusCode {

    protected $code;
    protected $text;

    public function __construct() {
        $this->code = 200;
        $this->text = '';
    }

    public function setCode(int $code): StatusCode {
        $this->code = $code;
        return $this;
    }

    public function setText(string $text): StatusCode {
        $this->text = $text;
        return $this;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function getText(): string {
        return $this->text;
    }
}
