<?php

namespace Test;

require dirname(__FILE__) . '/../Application.php';

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    
    public static function setUpBeforeClass(): void {
        new \Application(true);
    }
}
