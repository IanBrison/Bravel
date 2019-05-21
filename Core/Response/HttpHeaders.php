<?php

namespace Core\Response;

class HttpHeaders {

    protected $headers;

    public function __construct() {
        $this->headers = [];
    }

    public function addHeader(HttpHeader $header): HttpHeaders {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * @return HttpHeader[]
     */
    public function getHeaders(): array {
        return $this->headers;
    }
}
