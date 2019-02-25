<?php

namespace App\Controllers;

use Core\Controller\Controller;
use App\Services\ExampleService;

class ExampleController extends Controller {

    public function getWelcome() {
        $exampleService = new ExampleService();
        $welcomeInfoViewModel = $exampleService->getWelcomeInfo();
        $variables = [
            'welcomeInfo' => $welcomeInfoViewModel,
        ];
        return $this->render('welcome', $variables);
    }
}
