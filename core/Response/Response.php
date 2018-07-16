<?php

namespace Core\Response;

class Response {

    protected $content;
    protected $status_code = 200;
    protected $status_text = 'OK';
    protected $http_headers = array();

    public function send() {
        header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);

        foreach ($this->http_headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setStatusCode($status_code, $status_text = '') {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
    }

    public function setHttpHeader($name, $value) {
        $this->http_headers[$name] = $value;
    }
}
