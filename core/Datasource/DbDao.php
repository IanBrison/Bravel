<?php

namespace Core\Datasource;

use \PDO;
use Core\Di\DiContainer as Di;
use Core\Datasource\DbManager;

abstract class DbDao {

    protected $connectionName;

    public function __construct() {
        $this->connectionName = null;
    }

    public function connection(string $connectionName): DbDao {
        $this->connectionName = $connectionName;
        return $this;
    }

    public function getLastInsertId(string $columnName = 'id'): int {
        $id = Di::get(DbManager::class)->getConnection($this->connectionName)->lastInsertId($columnName);
        return (int)$id;
    }

    public function execute($sql, $params = array()) {
        $stmt = Di::get(DbManager::class)->getConnection($this->connectionName)->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetch($sql, $params = array()) {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql, $params = array()): array {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
