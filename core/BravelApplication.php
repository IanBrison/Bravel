<?php

namespace Core;

use Core\Request\Request;
use Core\Response\Response;
use Core\Session\Session;
use Core\Database\DbManager;
use Core\Routing\Router;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;

abstract class BravelApplication {

    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

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

    protected function initialize() {
        Environment::setConfigPath($this->getConfigDir());
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db_manager = new DbManager($this->getRepositoryDirNamespace(), $this->getDaoDirNamespace());
        $this->router = new Router($this->registerRoutes());
    }

    protected function configure() {
        $pdo_infos = Environment::getConfig('database');
        $foreach ($pdo_infos as $connection_name => $pdo_info) {
            $this->db_manager->connect($connection_name, $pdo_info);
        }
    }

    abstract public function getRootDir();

    abstract protected function registerRoutes();

    public function isDebugMode() {
        return $this->debug;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getSession() {
        return $this->session;
    }

    public function getDbManager() {
        return $this->db_manager;
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

        $controller = new $controller_class($this);
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
<!DOCTYPE html PUBLIC "~//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
