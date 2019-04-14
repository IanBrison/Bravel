<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Presenter\Builtins\Presenters\CsrfTokenPresenter;

trait BasicViewPresenter {

    public function viewTemplate(): string {
        if (!empty($this->viewTemplate)) {
	        return $this->viewTemplate;
        }
        if (!empty($this->template)) {
            return $this->template;
	    }
	    throw new \Exception('No view template specified');
    }

    public function presentView() {
        return Di::get(View::class)->render($this->viewTemplate(), ['vp' => $this]);
    }

    public function csrfTokenPresenter(): CsrfTokenPresenter {
        return Di::get(View::class)->generateCsrfTokenPresenter();
    }
}
