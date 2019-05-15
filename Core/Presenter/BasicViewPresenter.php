<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Presenter\Builtins\Presenters\CsrfTokenPresenter;
use Exception;

trait BasicViewPresenter {

    /**
     * @return string
     * @throws Exception
     */
    public function viewTemplate(): string {
        if (!empty($this->viewTemplate)) {
	        return $this->viewTemplate;
        }
        if (!empty($this->template)) {
            return $this->template;
	    }
	    throw new Exception('No view template specified');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function presentView() {
        return Di::get(View::class)->render($this->viewTemplate(), ['vp' => $this]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function presentCsrfTokenView() {
        /** @var CsrfTokenPresenter $csrfPresenter */
        $csrfPresenter = Di::get(View::class)->generateCsrfTokenPresenter();
        return $csrfPresenter->presentView();
    }
}
