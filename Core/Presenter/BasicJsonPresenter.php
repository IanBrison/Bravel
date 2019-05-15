<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Presenter\Builtins\Presenters\CsrfTokenPresenter;
use Exception;

trait BasicJsonPresenter {

    /**
     * @return string
     * @throws Exception
     */
    public function jsonTemplate(): string {
        if (!empty($this->jsonTemplate)) {
	        return $this->jsonTemplate;
        }
        if (!empty($this->template)) {
            return $this->template;
        }
	    throw new Exception('No json template specified');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function presentJson() {
        return Di::get(Json::class)->transform($this->jsonTemplate(), ['jp' => $this]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function presentCsrfTokenJson() {
        /** @var CsrfTokenPresenter $csrfPresenter */
        $csrfPresenter = Di::get(Json::class)->generateCsrfTokenPresenter();
        return $csrfPresenter->presentJson();
    }
}