<?php

namespace Core\Test;

use PHPUnit\Framework\TestCase;
use Core\BravelApplication;

abstract class BravelTestCase extends TestCase {
    
    public static function setUpBeforeClass(): void {
        new TestBravelApplication(true);
    }
}

class TestBravelApplication extends BravelApplication {

    protected $controllerDirNamespace = 'App\\Controllers\\';
    protected $configPath = '/config';

    public function getRootDir(): string {
        return dirname(__FILE__);
    }

    protected function registerRoutes(): array {
        return [];
    }

    protected function configure() {}
}
