<?php

namespace App\Domain\Repository;

use App\Domain\Model\ExampleModel;

interface ExampleRepository {

    public function getExampleModel(): ExampleModel;

}
