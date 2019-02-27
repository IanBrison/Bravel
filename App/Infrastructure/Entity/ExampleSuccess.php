<?php

namespace App\Infrastructure\Entity;

use App\Domain\Model\ExampleModel;

class ExampleSuccess implements ExampleModel {

    public function isConnected(): bool {
        return true;
    }
}
