<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Session\Session;
use Core\Presenter\BuiltIns\Presenters\CsrfTokenPresenter;

abstract class Presenter {

    const BRAVEL_CORE_TEMPLATE_DIRECTORY = '/core/Presenter/BuiltIns';

    protected function bravelCoreTemplateDirectory(string $path): string {
    	return self::BRAVEL_CORE_TEMPLATE_DIRECTORY . $path;
    }

    public function generateCsrfTokenPresenter(): CsrfTokenPresenter {
        $token = Di::get(Session::class)->generateCsrfToken();
        return Di::get(CsrfTokenPresenter::class, $token);
    }
}