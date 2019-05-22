<?php

namespace Core\Storage;

use Core\Environment\Environment;
use Core\Storage\StorageDriver\LocalDriver;
use Exception;

class Storage {

    private $locations;

    /**
     * Storage constructor.
     * @throws Exception
     */
    public function __construct() {
        $this->locations = Environment::getConfig('storage.locations');
    }

    /**
     * @param string $location
     * @return StorageDriver
     * @throws Exception
     */
    public function location(string $location): StorageDriver {
        $driverName = null;
        if (!isset($this->locations[$location]['driver'])) {
        } else if ($this->locations[$location]['driver'] === 'Local') {
            return new LocalDriver($this->locations[$location]['path']);
        }

        throw new Exception("No such location `$location` in storage config");
    }
}
