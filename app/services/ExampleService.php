<?php

namespace App\Services;

use Core\Di\DiContainer as Di;
use App\Repositories\ExampleRepository;
use Presentation\Models\WelcomeInfo;

class ExampleService {

    public function getWelcomeInfo(): WelcomeInfo {
        $exampleModel = Di::get(ExampleRepository::class)->getExampleModel();
        $welcomeInfoViewModel = new WelcomeInfo($exampleModel);

        return $welcomeInfoViewModel;
    }
}