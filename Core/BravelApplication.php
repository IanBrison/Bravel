<?php

namespace Core;

use Core\Environment\Environment;
use Core\Di\DiContainer as Di;
use Core\Response\Response;
use Core\Routing\Router;
use Core\Exception\BravelExceptionHandler;
use App\System\Exception\HttpNotFoundException;

abstract class BravelApplication {

    protected $debug = false;
    protected $loginUrl = '/login';
    protected $controllerDirNamespace = 'App\\System\\Controller\\';
    protected $routingDir = '/App/System/Routing/';
    protected $configPath = '/config';

    public function __construct($debug = false) {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /*
     * set to debug mode to stacktrace the errors when something occurs
     *
     * don't forget to unset it in production environment
     */
    public function setDebugMode($debug) {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }

    /*
     * initializes the Application class
     * you should not override this method unless you really need to
     *
     * use the 'configure' method instead
     */
    protected function initialize() {
        Environment::initialize($this->getRootDir(), $this->getConfigPath());
        Di::initialize();
    }

    // return the absolute RootDir Path for configuring relative Paths
    abstract public function getRootDir(): string;

    // configure things for the qpplication at the beginning
    abstract protected function configure();

    // return if the application is in debug mode
    public function isDebugMode(): bool {
        return $this->debug;
    }

    // return the url to redirect when not authorized
    public function getLoginUrl(): string {
        return $this->loginUrl;
    }

    // return the controllers base namespace for concatenating the class name
    public function getControllerDirNamespace(): string {
        return $this->controllerDirNamespace;
    }

    // return the routing directory to register
    public function getRoutingDir(): string {
        return Environment::getDir($this->routingDir);
    }

    // return the configuration path for configuring the application's settings
    public function getConfigPath(): string {
        return $this->configPath;
    }

    // collect the declared routes which you want to register
    private function collectRoutes(): array {
        $routes = [];
        foreach (glob($this->getRoutingDir() . "*.php") as $routingFilename) {
            $routes = array_merge($routes, require $routingFilename);
        }
        return $routes;
    }

    // handles the whole web application procedure
    public function run() {
        try {
            $action = Di::get(Router::class)->compileRoutes($this->collectRoutes())->resolve()->getAction();

            $controllerClass = $this->getControllerDirNamespace() . $action->getController();
            $controller = new $controllerClass();
            if ($controller === false) {
                throw new HttpNotFoundException($controllerClass . ' controller is not found.');
            }

            $controller->run($action->getMethod(), $action->getParams());
        } catch (\Throwable $e) {
            Di::get(BravelExceptionHandler::class, $e)->handle($this->isDebugMode());
        }

        Di::get(Response::class)->send();
    }
}
