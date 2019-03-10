<?php

namespace Core\View\BuiltIns\Models;

use Core\View\ViewModel;
use Core\Request\Request;

class CsrfToken extends ViewModel {

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
