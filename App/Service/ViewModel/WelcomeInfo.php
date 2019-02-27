<?php

namespace App\Service\ViewModel;

use Core\View\ViewModel;
use App\Domain\Model\ExampleModel;

class WelcomeInfo extends ViewModel {

    protected $template = 'welcome_info';

    private $exampleModel;

    public function __construct(ExampleModel $exampleModel) {
        $this->exampleModel = $exampleModel;
    }

    public function connectionStatus(): string {
        if ($this->exampleModel->isConnected()) {
            return 'Database Connected Successfully!!';
        }
        return 'Database Connection Failed!!';
    }
}
