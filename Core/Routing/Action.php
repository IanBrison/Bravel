<?php

namespace Core\Routing;

class Action {

    protected $controller; // the controller to use
    protected $method; // the action method to call
    protected $params; // the parameters within the url_path

    public function __construct(string $controller, string $method) {
        $this->controller = $controller;
        $this->method = $method;
        $this->params = array();
    }

    public function setParams(array $params): self {
        $this->params = $params;
        return $this;
    }

    public function getController(): string {
        return $this->controller;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getParams(): array {
        return $this->params;
    }
}
