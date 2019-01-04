<?php

namespace App\Models\Entity;

use App\Models\ExampleModel;

class ExampleSuccess implements ExampleModel {

    public function getWelcomeContent(): string {
        return 'Database Connected Successfully!!';
    }

}
