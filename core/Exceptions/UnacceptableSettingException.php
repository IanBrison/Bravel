<?php

namespace Core\Exceptions;

use \Exception;

class UnacceptableSettingException extends Exception implements BravelException {

    public function render($is_debub_mode = false) {
    }
}
