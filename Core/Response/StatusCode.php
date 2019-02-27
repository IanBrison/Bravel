<?php

namespace Core\Response;

use Core\Exceptions\UnacceptableSettingException;

class StatusCode {

    const AVAIABLE_CODES = [200, 302, 403, 404, 500];

    protected $code;
    protected $text;

    public function __construct() {
        $this->code = 200;
        $this->text = '';
    }

    public function setCode(int $code): StatusCode {
        if (in_array($code, self::AVAIABLE_CODES)) {
            $this->code = $code;
            return $this;
        }

        throw new UnacceptableSettingException();
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
