<?php

namespace App\Service\Repository;

use App\Model\Read\ExampleModel;

interface ExampleRepository {

    public function getExampleModel(): ExampleModel;

}
