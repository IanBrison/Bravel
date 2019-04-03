<?php

namespace App\Infrastructure\Query;

use Core\Datasource\DbDao;
use App\Service\Repository\ExampleRepository;
use App\Model\Read\ExampleModel;
use App\Infrastructure\Entity\ExampleSuccess;
use App\Infrastructure\Entity\ExampleFail;

class ExampleDbQuery extends DbDao implements ExampleRepository {

    public function getExampleModel(): ExampleModel {
        try {
            $sql = "SELECT 1";
            $this->execute($sql);
        } catch (\Throwable $e) {
            return new ExampleFail();
        }

        return new ExampleSuccess();
    }
}
