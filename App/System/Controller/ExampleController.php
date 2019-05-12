<?php

namespace App\System\Controller;

use Core\Controller\Controller;

class ExampleController extends Controller {

    public function getWelcome() {
        $this->render('welcome');
    }
}