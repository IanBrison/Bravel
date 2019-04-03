<?php

namespace App\Infrastructure\Entity;

use App\Model\Read\ExampleModel;

class ExampleSuccess implements ExampleModel {

    public function isConnected(): bool {
        return true;
    }
}
