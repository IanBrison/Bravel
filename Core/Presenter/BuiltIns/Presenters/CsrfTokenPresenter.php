<?php

namespace Core\Presenter\BuiltIns\Presenters;

use Core\Presenter\BasicJsonPresenter;
use Core\Request\Request;
use Core\Presenter\BasicViewPresenter;
use Core\Presenter\ViewPresenter;
use Core\Presenter\JsonPresenter;

class CsrfTokenPresenter implements ViewPresenter, JsonPresenter {

    use BasicViewPresenter, BasicJsonPresenter;

    protected $viewTemplate = 'csrf_token';
    protected $jsonTemplate = 'CsrfToken';

    private $_token;

    public function __construct(string $_token) {
        $this->_token = $_token;
    }

    public function token(): string {
        return $this->_token;
    }

    public function tokenFormName(): string {
        return Request::CSRF_TOKEN_FORM_NAME;
    }
}
