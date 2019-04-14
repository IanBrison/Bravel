<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Presenter\Builtins\Presenters\CsrfTokenPresenter;

trait BasicJsonPresenter {

    public function jsonTemplate(): string {
        if (!empty($this->jsonTemplate)) {
	        return $this->jsonTemplate;
        }
        if (!empty($this->template)) {
            return $this->template;
        }
	    throw new \Exception('No json template specified');
    }

    public function presentJson() {
        return Di::get(Json::class)->transform($this->jsonTemplate(), ['jp' => $this]);
    }

    public function csrfTokenPresenter(): CsrfTokenPresenter {
        return Di::get(View::class)->generateCsrfTokenPresenter();
    }
}