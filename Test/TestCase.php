<?php

namespace Test;

use App\Service\DiContainer as Di;
use Exception;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

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