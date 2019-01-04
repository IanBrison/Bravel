<?php

namespace App\Repositories\Dao;

use Core\Datasource\DbDao;
use App\Repositories\ExampleRepository;
use App\Models\ExampleModel;
use App\Models\Entity\ExampleSuccess;
use App\Models\Entity\ExampleFail;

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
