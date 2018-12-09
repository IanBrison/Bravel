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
use Core\Exceptions\UnexpectedException;

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
        Environment::setConfigPath($this->getConfigDir());
        Di::initialize();
        Di::set(View::class, new View($this->getViewDir()));
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

    public function getModelDirNamespace(): string {
        return 'App\\Models\\';
    }

    public function getEntityDirNamespace(): string {
        return 'App\\Models\\Entity\\';
    }

    public function getRepositoryDirNamespace(): string {
        return 'App\\Repositories\\';
    }

    public function getDaoDirNamespace(): string {
        return 'App\\Repositories\\Dao\\';
    }

    public function getViewDir(): string {
        return $this->getRootDir() . '/presentation/views';
    }

    public function getConfigDir(): string {
        return $this->getRootDir() . '/config';
    }

    public function getWebDir(): string {
        return $this->getRootDir() . '/web';
    }

    public function run() {
        try {
            $request = Di::get(Request::class);
            $params = Di::get(Router::class, $this->registerRoutes())->resolve($request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $e->render($this->isDebugMode());
        } catch (UnauthorizedActionException $e) {
            $e->setLoginUrl($this->login_url)->render($this->isDebugMode());
        } catch (\Throwable $e) {
            Di::get(UnexpectedException::class)->setException($e)->render();
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
