<?php

namespace Core\View;

use Core\Di\DiContainer as Di;
use Core\View\View;

abstract class ViewModel {

    protected $template;

    public function template(): string {
        return $this->template;
    }

    public function present() {
        return Di::get(View::class)->render($this->template(), ['vm' => $this]);
    }
}
