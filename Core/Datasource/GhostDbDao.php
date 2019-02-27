<?php

namespace Core\Datasource;

class GhostDbDao extends DbDao {

    private $container;

    public function __construct() {
        $this->container = array();
    }

    public function setGhost(GhostEntity $ghost) {
        $this->container[get_class($ghost)][$ghost->primaryKey()] = $ghost;
    }

    public function hasRealEntity(GhostEntity $ghost): bool {
        if (!isset($this->container[get_class($ghost)][$ghost->primaryKey()])) {
            return false;
        }
        if ($this->container[get_class($ghost)][$ghost->primaryKey()] instanceof GhostEntity) {
            return false;
        }
        return true;
    }

    public function getRealEntity(GhostEntity $ghost) {
        if (!isset($this->container[get_class($ghost)][$ghost->primaryKey()])) {
            $this->setGhost($ghost);
        }
        if (!($this->container[get_class($ghost)][$ghost->primaryKey()] instanceof GhostEntity)) {
            return $this->container[get_class($ghost)][$ghost->primaryKey()];
        }

        $this->bulkRealization(get_class($ghost));

        return $this->container[get_class($ghost)][$ghost->primaryKey()];
    }

    private function bulkRealization(string $containerName) {
        $lazyPrimaryKeys = array();
        $lazySqls = array();
        foreach ($this->container[$containerName] as $primaryKey => $entity) {
            if ($entity instanceof GhostEntity) {
                $lazyPrimaryKeys[] = $primaryKey;
                $lazySqls[] = $entity->realizeQuery();
            }
        }

        $rows = $this->fetchAll(implode(" UNION ", $lazySqls));
        foreach ($rows as $index => $row) {
            $realEntity = $this->container[$containerName][$lazyPrimaryKeys[$index]]->realizeConstruction($row);
            $this->container[$containerName][$lazyPrimaryKeys[$index]] = $realEntity;
        }
    }
}
