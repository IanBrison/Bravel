<?php

namespace Core\Test;

use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Exception;
use PHPUnit\Framework\TestCase;

abstract class BravelTestCase extends TestCase {

	public static function setUpBeforeClass(): void {
		Environment::initialize(__DIR__, '/config');
	}

	/**
	 * @throws Exception
	 */
	protected function setUp(): void {
		parent::setUp();
		Di::initialize();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Di::clear();
	}
}