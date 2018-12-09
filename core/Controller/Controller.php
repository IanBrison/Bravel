<?php

namespace Core\Controller;

use Core\Di\DiContainer as Di;
use Core\View\View;
use Core\Session\Session;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Response\HttpHeader;
use Core\Response\HttpHeaders;
use Core\Request\Request;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;

abstract class Controller {

    protected $controller_name;
    protected $action_method;

    protected $auth_actions = array();

    public function __construct() {
        $this->controller_name = get_class($this);
    }

    public function run($action_method, $params = array()) {
        $this->action_method = $action_method;
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        if ($this->needsAuthentication($action_method) && !Di::get(Session::class)->isAuthenticated()) {
            throw new UnauthorizedActionException();
        }

        $content = $this->$action_method($params);

        return $content;
    }

    public function render($variables = array(), $template = null, $layout = 'layout') {
        $view = Di::get(View::class);

        return $view->render($template, $variables, $layout);
    }

    protected function forward404() {
        throw Di::get(HttpNotFoundException::class)->setMessage('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_method);
    }

    protected function redirect($url) {
        if (!preg_match('#https?://#', $url)) {
            $request = Di::get(Request::class);
            $protocol = $request->isSsl() ? 'https://' : 'http://';
            $host = $request->getHost();
            $base_url = $request->getBaseUrl();

            $url = $protocol . $host . $base_url . $url;
        }

        $status_code = Di::get(StatusCode::class)->setCode(302)->setText('Found');
        $header = Di::get(HttpHeader::class)->setName('Location')->setValue($url);
        $http_headers = Di::get(HttpHeaders::class)->addHeader($header);
        Di::set(Response::class, Di::get(Response::class)->setStatusCode($status_code)->setHttpHeaders($http_headers));
    }

    protected function generateCsrfToken($form_name) {
        $session = Di::get(Session::class);
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $session->get($key, array());
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $session->set($key, $tokens);

        return $token;
    }

    protected function checkCsrfToken($form_name, $token): bool {
        $session = Di::get(Session::class);
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $session->get($key, array());

        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $session->set($key, $tokens);

            return true;
        }

        return false;
    }

    protected function needsAuthentication($action_method) {
        if ($this->auth_actions === true || (is_array($this->auth_actions) && in_array($action_method, $this->auth_actions))) {
            return true;
        }

        return false;
    }
}
