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
        $this->status_code = Di::get(StatusCode::class, 200);
        $this->content = Di::get(Content::class, '');
        $this->http_headers = Di::get(HttpHeaders::class, []);
    }

    public function send() {
        header('HTTP/1.1 ' . $this->status_code->getCode() . ' ' . $this->status_code->getText());

        foreach ($this->http_headers->getHeaders() as $http_header) {
            header($http_header->getName() . ': ' . $http_header->getValue());
        }

        echo $this->content->get();
    }
}
