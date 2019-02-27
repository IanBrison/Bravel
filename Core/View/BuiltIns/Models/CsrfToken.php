<?php

namespace Core\View\BuiltIns\Models;

use Core\View\ViewModel;

class CsrfToken extends ViewModel {

    protected $template = 'csrf_token';

    private $_token;

    public function __construct(string $_token) {
        $this->_token = $_token;
    }

    public function token(): string {
        return $this->_token;
    }
}
