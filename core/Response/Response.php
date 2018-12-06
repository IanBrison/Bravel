<?php

namespace Core\Response;

use Core\Di\DiContainer as Di;
use Core\Response\StatusCode;
use Core\Response\Content;
use Core\Response\HttpHeaders;

class Response {

    protected $content;
    protected $status_code;
    protected $http_headers;

    public function __construct() {
        $this->content = '';
        $this->status_code = Di::get(StatusCode::class);
        $this->http_headers = Di::get(HttpHeaders::class);
    }

    public function setContent(String $content): Response {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(StatusCode $status_code): Response {
        $this->status_code = $status_code;
        return $this;
    }

    public function setHttpHeaders(HttpHeaders $http_headers): Response {
        $this->http_headers = $http_headers;
        return $this;
    }

    public function send() {
        header('HTTP/1.1 ' . $this->status_code->getCode() . ' ' . $this->status_code->getText());

        foreach ($this->http_headers->getHeaders() as $http_header) {
            header($http_header->getName() . ': ' . $http_header->getValue());
        }

        echo $this->content;
    }
}
