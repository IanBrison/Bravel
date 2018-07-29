<?php

namespace Core;

use Core\Environment\Environment;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Response\Response;
use Core\Session\Session;
use Core\Database\DbManager;
use Core\Routing\Router;
use Core\View\View;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;

abstract class BravelApplication {

    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $router;

    public function __construct($debug = false) {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

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
        $this->request = Di::get(Request::class);
        $this->response = Di::get(Response::class);
        $this->session = Di::get(Session::class);
        $this->router = Di::get(Router::class, $this->registerRoutes());
        Di::set(DbManager::class, new DbManer($this->getRepositoryDirNamespace(), $this->getDaoDirNamespace()));
        Di::set(View::class, new View($this->getViewDir()));
    }

    abstract public function getRootDir();

    abstract protected function registerRoutes();

    abstract protected function configure();

    public function isDebugMode() {
        return $this->debug;
    }

    public function getControllerDirNamespace() {
        return 'App\\Controllers\\';
    }

    public function getModelDirNamespace() {
        return 'App\\Models\\';
    }

    public function getEntityDirNamespace() {
        return 'App\\Models\\Entity\\';
    }

    public function getRepositoryDirNamespace() {
        return 'App\\Repositories\\';
    }

    public function getDaoDirNamespace() {
        return 'App\\Repositories\\Dao\\';
    }

    public function getViewDir() {
        return $this->getRootDir() . '/presentation/views';
    }

    public function getConfigDir() {
        return $this->getRootDir() . '/config';
    }

    public function getWebDir() {
        return $this->getRootDir() . '/app/web';
    }

    public function run() {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);
        } catch (UnauthorizedActionException $e) {
            list($controller, $action) = $this->login_action;
            $this->runAction($controller, $action);
        }

        $this->response->send();
    }

    public function runAction($controller_name, $action, $params = array()) {
        $controller_class = $this->getControllerDirNamespace() . $controller_name;

        $controller = new $controller_class();
        if ($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        $this->response->setContent($content);
    }

    protected function render404Page($e) {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(<<<EOF
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>404</title>
</head>
<body>
    {$message}
</body>
</html>
EOF
        );
    }
}
