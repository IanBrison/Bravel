<?php

namespace Core\View\BuiltIns\ViewModels;

use Core\View\ViewModel;
use Core\View\BasicViewModel;
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
