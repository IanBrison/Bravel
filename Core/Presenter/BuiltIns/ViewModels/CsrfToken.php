<?php

namespace Core\Presenter\BuiltIns\ViewModels;

use Core\Presenter\ViewModel;
use Core\Presenter\BasicViewModel;
use Core\Request\Request;

class CsrfToken implements ViewModel {

    use BasicViewModel;

    protected $template = 'csrf_token';

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
