<?php

use Core\Routing\Router;

return [
    Router::get('/', 'ExampleController', 'getWelcome'),
];
