<?php

namespace Core\Routing;

class Action {

    protected $controller; // the controller to use
    protected $method; // the action method to call
    protected $params; // the parameters within the url_path
    protected $redirectUrl; // the url to redirect

    public function __construct(string $controller, string $method) {
        $this->controller = $controller;
        $this->method = $method;
        $this->params = array();
        $this->redirectUrl = '';
    }

    public function setParams(array $params): self {
        $this->params = $params;
        return $this;
    }

    public function setRedirect(string $url): self {
        $this->redirectUrl = $url;
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

    public function getRedirectUrl(): string {
        return $this->redirectUrl;
    }
}
