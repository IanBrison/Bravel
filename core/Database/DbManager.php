<?php

namespace Core\Database;

use Core\Environment\Environment;
use \PDO;

class DbManager {

    protected $repository_namespace;
    protected $dao_namespace;
    protected $connections = array();
    protected $repository_connection_map = array();
    protected $repositories = array();

    public function __construct($repository_namespace, $dao_namespace) {
        $this->repository_namespace = $repository_namespace;
        $this->dao_namespace = $dao_namespace;

        $pdo_infos = Environment::getConfig('database');
        foreach ($pdo_infos as $connection_name => $pdo_info) {
            $this->connect($connection_name, $pdo_info);
        }
    }

    public function connect($name, $params) {
        $params = array_merge(array(
            'dsn'      => null,
            'user'     => '',
            'password' => '',
            'options'  => array(),
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$name] = $con;
    }

    public function getConnection($name = null) {
        if (is_null($name)) {
            return current($this->connections);
        }

        return $this->connections[$name];
    }

    public function setRepositoryConnectionMap($repository_name, $name) {
        $this->repository_connection_map[$repository_name] = $name;
    }

    public function getConnectionForRepository($repository_name) {
        if (isset($this->repository_connection_map[$repository_name])) {
            $name = $this->repository_connection_map[$repository_name];
            $con = $this->getConnection($name);
        } else {
            $con = $this->getConnection();
        }

        return $con;
    }

    public function get($repository_name) {
        if (!isset($this->repositories[$repository_name])) {
            $dao_class = str_replace($this->repository_namespace, $this->dao_namespace, $repository_name);
            $con = $this->getConnectionForRepository($repository_name);

            $dao = new $dao_class($con);

            $this->repositories[$repository_name] = $dao;
        }

        return $this->repositories[$repository_name];
    }

    public function __destruct() {
        foreach ($this->repositories as $repository) {
            unset($repository);
        }

        foreach ($this->connections as $con) {
            unset($con);
        }
    }
}
