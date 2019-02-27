<?php

namespace Core\Datasource;

use Core\Di\DiContainer as Di;

abstract class GhostEntity {

    protected $primaryKey;
    protected $realEntity;

    abstract public function realizeQuery(): string;
    abstract public function realizeConstruction($row);

    public function __construct($primaryKey) {
        $this->primaryKey = $primaryKey;
        $this->realEntity = null;

        $ghostDao = Di::get(GhostDbDao::class);
        if ($ghostDao->hasRealEntity($this)) {
            $this->realEntity = $ghostDao->getRealEntity($this);
        } else {
            $ghostDao->setGhost($this);
        }
    }

    public function primaryKey() {
        return $this->primaryKey;
    }

    protected function realize() {
        if (!is_null($this->realEntity)) {
            return $this->realEntity;
        }

        $realEntity = Di::get(GhostDbDao::class)->getRealEntity($this);

        $this->realEntity = $realEntity;
        return $this->realEntity;
    }
}
