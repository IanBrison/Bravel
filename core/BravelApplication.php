<?php

namespace Core;

use Core\Environment\Environment;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Routing\Router;
use Core\View\View;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;
use Core\Exceptions\BravelExceptionHandler;

use \Throwable;

abstract class BravelApplication {

    protected $debug = false;

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

    abstract public function getRootDir(): string;

    abstract protected function registerRoutes(): array;

    abstract protected function configure();

    public function isDebugMode(): bool {
        return $this->debug;
    }

    public function getControllerDirNamespace(): string {
        return 'App\\Controllers\\';
    }

    public function getConfigPath(): string {
        return '/config';
    }

    public function run() {
        try {
            $params = Di::get(Router::class, $this->registerRoutes())->resolve();

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $e->handle($this->isDebugMode());
        } catch (UnauthorizedActionException $e) {
            $e->setLoginUrl($this->login_url)->handle($this->isDebugMode());
        } catch (Throwable $e) {
            Di::get(BravelExceptionHandler::class, $e)->handle($this->isDebugMode());
        }

        Di::get(Response::class)->send();
    }

    public function runAction($controller_name, $action, $params = array()) {
        $controller_class = $this->getControllerDirNamespace() . $controller_name;

        $controller = new $controller_class();
        if ($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        Di::set(Response::class, Di::get(Response::class)->setContent($content));
    }
}
