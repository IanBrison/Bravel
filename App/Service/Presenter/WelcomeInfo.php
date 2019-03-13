<?php

namespace App\Service\Presenter;

use Core\Presenter\ViewModel;
use Core\Presenter\BasicViewModel;
use App\Domain\Model\ExampleModel;

class WelcomeInfo implements ViewModel {

    use BasicViewModel;

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
