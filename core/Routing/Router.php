<?php

namespace Core\Routing;

use Exception;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Session\Session;
use Core\Exceptions\UnauthorizedActionException;
use Core\Exceptions\HttpNotFoundException;

class Router {

    protected $getRoutes;
    protected $postRoutes;

    public function __construct() {
        $this->getRoutes = array();
        $this->postRoutes = array();
    }

    public function compileRoutes(array $routes): self {
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
                $this->getRoutes[$pattern] = $route;
            } else if ($route instanceof PostRoute) {
                $this->postRoutes[$pattern] = $route;
            }
        }

        return $this;
    }

    public function resolve(): Action {
        $pathInfo = Di::get(Request::class)->getPathInfo();
        if ('/' !== substr($pathInfo, 0, 1)) {
            $pathInfo = '/' . $pathInfo;
        }

        $routes = Di::get(Request::class)->isPost() ? $this->postRoutes : $this->getRoutes;
        foreach ($routes as $pattern => $route) {
            if (preg_match('#^' . $pattern . '$#', $pathInfo, $matches)) {
                if ($route->needsAuth() && !Di::get(Session::class)->isAuthenticated()) {
                    throw new UnauthorizedActionException();
                }
                if (Di::get(Request::class)->isPost() && !Di::get(Session::class)->checkCsrfToken(Di::get(Request::class)->getPost('_token'))) {
                    throw new Exception("Doesn't have the csrf_token for ".$route->getUrlPath());
                }
                $params = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                return $route->getAction()->setParams($params);
            }
        }

        throw new HttpNotFoundException('No route found for ' . $pathInfo);
    }

    public static function get(string $urlPath, string $controller, string $method): GetRoute {
        return new GetRoute($urlPath, new Action($controller, $method));
    }

    public static function post(string $urlPath, string $controller, string $method): PostRoute {
        return new PostRoute($urlPath, new Action($controller, $method));
    }
}
