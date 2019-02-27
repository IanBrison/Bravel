<?php

namespace App\Infrastructure\Entity;

use App\Domain\Model\ExampleModel;

class ExampleFail implements ExampleModel {

    public function isConnected(): bool {
        return false;
    }
}
