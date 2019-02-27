<?php

namespace Core\Response;

use Core\Di\DiContainer as Di;
use Core\Response\StatusCode;
use Core\Response\Content;
use Core\Response\HttpHeaders;

class Response {

    protected $content;
    protected $statusCode;
    protected $httpHeaders;

    public function __construct() {
        $this->content = '';
        $this->statusCode = Di::get(StatusCode::class);
        $this->httpHeaders = Di::get(HttpHeaders::class);
    }

    public function setContent(String $content): self {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(StatusCode $statusCode): self {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHttpHeaders(HttpHeaders $httpHeaders): self {
        $this->httpHeaders = $httpHeaders;
        return $this;
    }

    public function send() {
        header('HTTP/1.1 ' . $this->statusCode->getCode() . ' ' . $this->statusCode->getText());

        foreach ($this->httpHeaders->getHeaders() as $httpHeader) {
            header($httpHeader->getName() . ': ' . $httpHeader->getValue());
        }

        echo $this->content;
    }
}
