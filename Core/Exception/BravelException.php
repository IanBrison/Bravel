<?php

namespace Core\Exception;

interface BravelException {

    public function handle($isDebugMode);
}
