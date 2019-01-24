<?php

namespace Core\Routing;

use Exception;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Session\Session;
use Core\Exceptions\UnauthorizedActionException;
use Core\Exceptions\HttpNotFoundException;

class Router {

    protected $get_routes;
    protected $post_routes;

    public function __construct() {
        $this->get_routes = array();
        $this->post_routes = array();
    }

    public function compileRoutes(array $routes) {
        foreach ($routes as $route) {
            $tokens = explode('/', ltrim($route->getUrlPath(), '/'));
            foreach ($tokens as $i => $token) {
                if (0 === strpos($token, ':')) {
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            $pattern = '/' . implode('/', $tokens);

            if ($route instanceof GetRoute) {
                $this->get_routes[$pattern] = $route;
            } else if ($route instanceof PostRoute) {
                $this->post_routes[$pattern] = $route;
            }
        }

        return $this;
    }

    public function resolve(): Action {
        $path_info = Di::get(Request::class)->getPathInfo();
        if ('/' !== substr($path_info, 0, 1)) {
            $path_info = '/' . $path_info;
        }

        $routes = Di::get(Request::class)->isPost() ? $this->post_routes : $this->get_routes;
        foreach ($routes as $pattern => $route) {
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                if ($route->needsAuth() && !Di::get(Session::class)->isAuthenticated()) {
                    throw new UnauthorizedActionException();
                }
                if (Di::get(Request::class)->isPost() && !Di::get(Session::class)->checkCsrfToken($route->getUrlPath(), Di::get(Request::class)->getPost('_token'))) {
                    throw new Exception("Doesn't have the csrf_token for ".$route->getUrlPath());
                }
                return $route->getAction()->setParams($matches);
            }
        }

        throw new HttpNotFoundException('No route found for ' . $path_info);
    }

    public static function get(string $url_path, string $controller, string $method): GetRoute {
        return new GetRoute($url_path, new Action($controller, $method));
    }

    public static function post(string $url_path, string $controller, string $method): PostRoute {
        return new PostRoute($url_path, new Action($controller, $method));
    }
}
