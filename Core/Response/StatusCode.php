<?php

namespace Core\Response;

use App\System\Exception\UnacceptableSettingException;

class StatusCode {

    const AVAILABLE_CODES = [200, 302, 403, 404, 500];

    protected $code;
    protected $text;

    public function __construct() {
        $this->code = 200;
        $this->text = '';
    }

	/**
	 * @param int $code
	 * @return StatusCode
	 * @throws UnacceptableSettingException
	 */
	public function setCode(int $code): StatusCode {
        if (in_array($code, self::AVAILABLE_CODES)) {
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
