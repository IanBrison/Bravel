<?php

namespace Core;

use \Throwable;
use Core\Environment\Environment;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Routing\Router;
use Core\Routing\Action;
use Core\View\View;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;
use Core\Exceptions\BravelExceptionHandler;

abstract class BravelApplication {

    protected $debug = false;
    protected $controllerDirNamespace = 'App\\Controllers\\';
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

    abstract public function getRootDir(): string;

    abstract protected function registerRoutes(): array;

    abstract protected function configure();

    public function isDebugMode(): bool {
        return $this->debug;
    }

    public function getLoginUrl(): string {
        return $this->loginUrl;
    }

    public function getControllerDirNamespace(): string {
        return $this->controllerDirNamespace;
    }

    public function getConfigPath(): string {
        return $this->configPath;
    }

    public function run() {
        try {
            $action = Di::get(Router::class)->compileRoutes($this->registerRoutes())->resolve();

            $this->runAction($action);
        } catch (HttpNotFoundException $e) {
            $e->handle($this->isDebugMode());
        } catch (UnauthorizedActionException $e) {
            $e->setLoginUrl($this->getLoginUrl())->handle($this->isDebugMode());
        } catch (Throwable $e) {
            Di::get(BravelExceptionHandler::class, $e)->handle($this->isDebugMode());
        }

        Di::get(Response::class)->send();
    }

    public function runAction(Action $action) {
        $controllerClass = $this->getControllerDirNamespace() . $action->getController();

        $controller = new $controllerClass();
        if ($controller === false) {
            throw new HttpNotFoundException($controllerClass . ' controller is not found.');
        }

        $content = $controller->run($action->getMethod(), $action->getParams());

        Di::set(Response::class, Di::get(Response::class)->setContent($content));
    }
}
