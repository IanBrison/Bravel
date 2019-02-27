<?php

namespace Core\Routing;

class PostRoute extends Route {

    public function isGet(): bool {
        return false;
    }
}
