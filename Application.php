<?php

use Core\BravelApplication;
use Core\Routing\Router;

class Application extends BravelApplication {

    protected $loginUrl = '/login';

    public function getRootDir(): string {
        return dirname(__FILE__);
    }

    /*
     * configure method runs right after the Application class is initialized
     * write whatever you want the Application to build or run before the whole process starts
     */
    protected function configure() {}
}
