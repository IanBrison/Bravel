<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Presenter\View;
use Core\Presenter\Builtins\ViewModels\CsrfToken;

trait BasicViewModel {

    public function template(): string {
        return $this->template;
    }

    public function present() {
        return Di::get(View::class)->render($this->template(), ['vm' => $this]);
    }

    public function csrfViewModel(): CsrfToken {
        return Di::get(View::class)->generateCsrfTokenViewModel();
    }
}
