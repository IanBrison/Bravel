<?php

namespace Core\Storage;

use Core\Environment\Environment;
use Core\Storage\StorageDriver\LocalDriver;

class Storage {

    private $locations;

    public function __construct() {
        $this->locations = Environment::getConfig('storage.locations');
    }

    public function location(string $location): StorageDriver {
        $driverName = null;
        if (!isset($this->locations[$location]['driver'])) {
        } else if ($this->locations[$location]['driver'] === 'Local') {
            return new LocalDriver($this->locations[$location]['path']);
        }

        throw new Exception("No such location `$location` in storage config");
    }
}
