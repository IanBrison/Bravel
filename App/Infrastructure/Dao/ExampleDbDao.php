<?php

namespace App\Infrastructure\Dao;

use Core\Datasource\DbDao;
use App\Domain\Repository\ExampleRepository;
use App\Domain\Model\ExampleModel;
use App\Infrastructure\Entity\ExampleSuccess;
use App\Infrastructure\Entity\ExampleFail;

class ExampleDbDao extends DbDao implements ExampleRepository {

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
