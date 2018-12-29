<?php

namespace App\Controllers;

use Core\Controller\Controller;

class ExampleController extends Controller {

    public function getWelcome() {
        $variables = [
        ];
        return $this->render('welcome.twig', $variables);
    }
}
