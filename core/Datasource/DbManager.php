<?php

namespace Core\Datasource;

use Core\Environment\Environment;
use \PDO;

class DbManager {

    protected $default_connection_name;
    protected $connections;
    protected $connection_params;

    public function __construct() {
        $database_settings = Environment::getConfig('database');

        $this->default_connection_name = $database_settings['options']['default'];
        $this->connections = array();
        $this->connection_params = array();
        foreach ($database_settings['connections'] as $connection_name => $pdo_info) {
            $this->connection_params[$connection_name] = $this->getParams($pdo_info);
        }
    }

    public function getParams(array $params): array {
        return array_merge(array(
            'dsn'      => null,
            'user'     => '',
            'password' => '',
            'options'  => array(),
        ), $params);
    }

    public function getConnection(?String $connection_name = null) {
        $connection_name = $connection_name ?? $this->default_connection_name;
        return $this->connections[$connection_name] ?? $this->_getConnection($connection_name);
    }

    private function _getConnection(String $connection_name): PDO {
        $params = $this->connection_params[$connection_name];
        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$connection_name] = $con;
        return $this->connections[$connection_name];
    }

    public function __destruct() {
        foreach ($this->connections as $con) {
            unset($con);
        }
    }
}
