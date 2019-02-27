<?php

namespace Core\Routing;

abstract class Route {

    protected $urlPath; // the path this route serves
    protected $action; // the action this route executes
    protected $needsAuth; // whether the route needs authentication

    public function __construct(string $urlPath, Action $action) {
        $this->urlPath = $urlPath;
        $this->action = $action;
        $this->needsAuth = false;
    }

    public function withAuth(string $redirectUrl = ''): self {
        $this->needsAuth = true;
        $this->action->setRedirect($redirectUrl);
        return $this;
    }

    public function getUrlPath(): string {
        return $this->urlPath;
    }

    public function getAction(): Action {
        return $this->action;
    }

    public function needsAuth(): bool {
        return $this->needsAuth;
    }

    abstract public function isGet(): bool;
}
