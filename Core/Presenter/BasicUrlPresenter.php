<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Exception;

trait BasicUrlPresenter {

    /**
     * @return string
     * @throws Exception
     */
    public function redirectUrl(): string {
        if (!empty($this->redirectUrl)) {
	        return $this->redirectUrl;
        }
        if (!empty($this->template)) {
            return $this->template;
        }
		throw new Exception('No redirect url template specified');
	}

    /**
     * @return mixed
     * @throws Exception
     */
    public function presentUrl() {
		return Di::get(Url::class)->redirect($this->redirectUrl());
	}
}