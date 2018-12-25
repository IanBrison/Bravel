<?php

namespace App\Controllers;

use Core\Controller\Controller;

class ExampleController extends Controller {

    public function getWelcome() {
        return $this->render([], 'welcome', null);
    }
}
