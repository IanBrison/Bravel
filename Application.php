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

    protected function configure() {}
}
