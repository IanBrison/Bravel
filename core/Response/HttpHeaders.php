<?php

namespace Core\Response;

use Core\Response\HttpHeader;

class HttpHeaders {

    protected $http_headers;

    public function __construct(?HttpHeader $http_header = null, ?HttpHeaders $http_headers = null) {
        $this->http_headers = is_null($http_headers) ? array() : $http_headers->getHeaders();
        if (!is_null($http_header)) {
            array_push($this->http_headers, $http_header);
        }
    }

    public function getHeaders(): array {
        return $this->http_headers;
    }
}
