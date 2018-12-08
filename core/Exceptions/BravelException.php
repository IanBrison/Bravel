<?php

namespace Core\Exceptions;

interface BravelException {

    public function render($is_debub_mode = false);
}
