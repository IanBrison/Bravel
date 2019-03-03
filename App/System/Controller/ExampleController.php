<?php

namespace App\System\Controller;

use Core\Controller\Controller;
use App\Service\Usecase\ExampleService;

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
