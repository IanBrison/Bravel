<?php

use PHPUnit\Framework\TestCase;
use Core\BravelApplication;

class BravelApplicationTest extends TestCase
{

    public function testApplication(): void
    {
        $app = new MockBravelApplication();

        $this->assertEquals($app->getLoginUrl(), '/login');
    }
}

class MockBravelApplication extends BravelApplication {
    protected $loginUrl = '/login';

    public function getRootDir(): string {
        return dirname(__FILE__) . '/../..';
    }

    protected function registerRoutes(): array {
        return [];
    }

    protected function configure() {}
}
