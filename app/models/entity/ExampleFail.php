<?php

namespace App\Models\Entity;

use App\Models\ExampleModel;

class ExampleFail implements ExampleModel {

    public function getWelcomeContent(): string {
        return 'Database Connection Failed!!';
    }

}
