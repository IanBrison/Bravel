<?php

namespace Core\Response;

use Core\Response\HttpHeader;

class HttpHeaders {

    protected $headers;

    public function __construct() {
        $this->headers = [];
    }

    public function addHeader(HttpHeader $header): HttpHeaders {
        $this->headers[] = $header;
        return $this;
    }

    public function getHeaders(): array {
        return $this->headers;
    }
}
