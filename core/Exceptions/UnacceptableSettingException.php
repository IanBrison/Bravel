<?php

namespace Core\Exceptions;

use \Exception;

class UnacceptableSettingException extends Exception implements BravelException {

    public function handle($is_debub_mode = false) {
    }
}
