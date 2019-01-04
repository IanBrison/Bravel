<?php

namespace App\Repositories;

use App\Models\ExampleModel;

interface ExampleRepository {

    public function getExampleModel(): ExampleModel;

}
