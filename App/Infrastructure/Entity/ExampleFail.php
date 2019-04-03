<?php

namespace App\Infrastructure\Entity;

use App\Model\Read\ExampleModel;

class ExampleFail implements ExampleModel {

    public function isConnected(): bool {
        return false;
    }
}
