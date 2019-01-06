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

    protected $auth_actions = array();

    public function __construct() {
        $this->controller_name = get_class($this);
    }

    public function run($method, $params = array()) {
        if (!method_exists($this, $method)) {
            throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $method);
        }

        $content = $this->$method($params);

        return $content;
    }

    public function render(string $template, array $variables = array()) {
        return Di::get(View::class)->render($template, $variables);
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
}
