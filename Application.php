<?php

use Core\BravelApplication;

class Application extends BravelApplication {

    protected $login_action = array();

    public function getRootDir() {
        return dirname(__FILE__);
    }

    protected function registerRoutes() {
        return array();
    }

    /*
     * configure method runs right after the Application class is initialized
     * write whatever you want the Application to build or run before the whole process starts
     */
    protected function configure() {}
}
