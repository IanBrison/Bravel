<?php

namespace Core\DataSource;

use Core\Di\DiContainer as Di;
use PDO;
use PDOStatement;

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

    public function execute(string $sql, $params = array()) {
        /** @var PDOStatement $stmt */
        $stmt = Di::get(DbManager::class)->getConnection($this->connectionName)->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetch(string $sql, $params = array()) {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(string $sql, $params = array()): array {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
