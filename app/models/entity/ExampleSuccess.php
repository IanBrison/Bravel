<?php

namespace App\Models\Entity;

use App\Models\ExampleModel;

class ExampleSuccess implements ExampleModel {

    public function isConnected(): bool {
        return true;
    }
}
