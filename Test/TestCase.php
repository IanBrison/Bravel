<?php

namespace Test;

use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

	public static function setUpBeforeClass(): void {
		Environment::initialize(dirname(__FILE__) . '/..', '/config');
	}

	/**
	 * @throws \Exception
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
