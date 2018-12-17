<?php

namespace Core\Exceptions;

interface BravelException {

    public function handle($is_debub_mode = false);
}
