<?php

namespace Core\DataSource;

use PDO;
use Exception;
use Core\Environment\Environment;

class DbManager {

	protected $defaultConnectionName;
	protected $connections;
	protected $connectionParams;

	/**
	 * DbManager constructor.
	 * @throws Exception
	 */
	public function __construct() {
		$databaseSettings = Environment::getConfig('database');

		$this->defaultConnectionName = $databaseSettings['options']['default'];
		$this->connections = array();
		$this->connectionParams = array();
		foreach ($databaseSettings['connections'] as $connectionName => $pdoInfo) {
			$this->connectionParams[$connectionName] = $this->getParams($pdoInfo);
		}
	}

	public function getParams(array $params): array {
		return array_merge(array(
			'dsn'	  => null,
			'user'	 => '',
			'password' => '',
			'options'  => array(),
		), $params);
	}

	/**
	 * @param String|null $connectionName
	 * @return PDO
	 * @throws Exception
	 */
	public function getConnection(?String $connectionName = null): PDO {
		$connectionName = $connectionName ?? $this->defaultConnectionName;
		return $this->connections[$connectionName] ?? $this->_getConnection($connectionName);
	}

	/**
	 * @param String $connectionName
	 * @return PDO
	 * @throws Exception
	 */
	private function _getConnection(String $connectionName): PDO {
		if (empty($this->connectionParams[$connectionName])) {
			throw new Exception("No db connection params configured for `$connectionName`");
		}
		$params = $this->connectionParams[$connectionName];
		$con = new PDO(
			$params['dsn'],
			$params['user'],
			$params['password'],
			$params['options']
		);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->connections[$connectionName] = $con;
		return $this->connections[$connectionName];
	}

	public function __destruct() {
		foreach ($this->connections as $con) {
			unset($con);
		}
	}
}
