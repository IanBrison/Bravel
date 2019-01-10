<?php

namespace App\Controllers;

use Core\Controller\Controller;
use Core\Di\DiContainer as Di;
use App\Repositories\ExampleRepository;

class ExampleController extends Controller {

    public function getWelcome() {
        $example_model = Di::get(ExampleRepository::class)->getExampleModel();
        $variables = [
            'example_model' => $example_model,
        ];
        return $this->render('welcome', $variables);
    }
}
