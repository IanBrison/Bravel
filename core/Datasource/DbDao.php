<?php

namespace Core\Datasource;

use \PDO;
use Core\Di\DiContainer as Di;
use Core\Datasource\DbManager;

abstract class DbDao {

    protected $connection_name;

    public function __construct() {
        $this->connection_name = null;
    }

    public function setConnectionName($connection_name) {
        $this->connection_name = $connection_name;
        return $this;
    }

    public function execute($sql, $params = array()) {
        $stmt = Di::get(DbManager::class)->getConnection($this->connection_name)->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetch($sql, $params = array()) {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql, $params = array()) {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
