<?php

namespace Core\Routing;

use Exception;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Session\Session;
use App\System\Exception\UnauthorizedActionException;
use App\System\Exception\HttpNotFoundException;

class Router {

    private $getRoutes;  // the given get routes
    private $postRoutes; // the given post routes

    public function __construct() {
        $this->getRoutes = array();
        $this->postRoutes = array();
    }

    // compile the given routes
    public function compileRoutes(array $routes): self {
        foreach ($routes as $route) {
            if (is_array($route)) {
                // compile the routes recursively
                $this->compileRoutes($route);
                continue;
            }
            $tokens = explode('/', ltrim($route->getUrlPath(), '/'));
            foreach ($tokens as $i => $token) {
                if (0 === strpos($token, ':')) {
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            $pattern = '/' . implode('/', $tokens);

            if ($route->isGet()) {
                $this->getRoutes[$pattern] = $route;
            } else {
                $this->postRoutes[$pattern] = $route;
            }
        }

        return $this;
    }

    // resolve the specific action from the requested route
    public function resolve(): self {
        $pathInfo = Di::get(Request::class)->getPathInfo();
        if ('/' !== substr($pathInfo, 0, 1)) {
            $pathInfo = '/' . $pathInfo;
        }

        $routes = Di::get(Request::class)->isPost() ? $this->postRoutes : $this->getRoutes;
        foreach ($routes as $pattern => $route) {
            if (preg_match('#^' . $pattern . '$#', $pathInfo, $matches)) {
                if ($route->needsAuth() && !Di::get(Session::class)->isAuthenticated()) {
                    Di::set(Action::class, $route->getAction());
                    throw new UnauthorizedActionException();
                }
                if (Di::get(Request::class)->isPost() && !Di::get(Session::class)->checkCsrfToken(Di::get(Request::class)->getCsrfToken())) {
                    throw new Exception("Doesn't have the csrf_token for ".$route->getUrlPath());
                }
                $params = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                Di::set(Action::class, $route->getAction()->setParams($params));
                return $this;
            }
        }

        throw new HttpNotFoundException('No route found for ' . $pathInfo);
    }

    // return the resolved Action
    public function getAction(): Action {
        return Di::get(Action::class);
    }

    // set a get route
    public static function get(string $urlPath, string $controller, string $method): GetRoute {
        return new GetRoute($urlPath, new Action($controller, $method));
    }

    // set a post route
    public static function post(string $urlPath, string $controller, string $method): PostRoute {
        return new PostRoute($urlPath, new Action($controller, $method));
    }

    // group the same urlPaths to shorten the declaration
    public static function group(string $groupUrlPath, array $routes): array {
        return array_map(function($route) use ($groupUrlPath) {
            if (is_array($route)) {
                // group the routes recursively
                return self::group($groupUrlPath, $route);
            }
            $routeClassName = get_class($route);
            return new $routeClassName($groupUrlPath . $route->getUrlPath(), $route->getAction());
        }, $routes);
    }
}
