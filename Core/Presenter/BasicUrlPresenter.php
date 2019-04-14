<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;

trait BasicUrlPresenter {

	public function redirectUrl(): string {
        if (!empty($this->redirectUrl)) {
	        return $this->redirectUrl;
        }
        if (!empty($this->template)) {
            return $this->template;
        }
		throw new \Exception('No redirect url template specified');
	}

	public function presentUrl() {
		Di::get(Url::class)->redirect($this->redirectUrl());
	}
}