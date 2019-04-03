<?php

namespace App\Service\Usecase;

use Core\Di\DiContainer as Di;
use App\Service\Repository\ExampleRepository;
use App\Service\Presenter\WelcomeInfo;

class ExampleService {

    public function getWelcomeInfo(): WelcomeInfo {
        $exampleModel = Di::get(ExampleRepository::class)->getExampleModel();
        $welcomeInfoViewModel = Di::get(WelcomeInfo::class, $exampleModel);

        return $welcomeInfoViewModel;
    }
}
