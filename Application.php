<?php

use Core\BravelApplication;

class Application extends BravelApplication {

    protected $login_url = '/login';

    public function getRootDir(): string {
        return dirname(__FILE__);
    }

    protected function registerRoutes(): array {
        return array();
    }

    /*
     * configure method runs right after the Application class is initialized
     * write whatever you want the Application to build or run before the whole process starts
     */
    protected function configure() {}
}
