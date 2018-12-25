<?php

namespace Core\Routing;

abstract class Route {

    protected $url_path; // the path this route serves
    protected $action; // the action this route executes
    protected $needs_auth; // whether the route needs authentication

    public function __construct(string $url_path, Action $action) {
        $this->url_path = $url_path;
        $this->action = $action;
        $this->needs_auth = false;
    }

    public function withAuth(): self {
        $this->needs_auth = true;
        return $this;
    }

    public function getUrlPath(): string {
        return $this->url_path;
    }

    public function getAction(): Action {
        return $this->action;
    }

    public function needsAuth(): bool {
        return $this->needs_auth;
    }
}
