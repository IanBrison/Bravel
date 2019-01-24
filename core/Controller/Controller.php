<?php

namespace Core\Controller;

use Core\Di\DiContainer as Di;
use Core\View\View;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Response\HttpHeader;
use Core\Response\HttpHeaders;
use Core\Request\Request;
use Core\Exceptions\HttpNotFoundException;
use Core\Exceptions\UnauthorizedActionException;

abstract class Controller {

    protected $controllerName;

    public function __construct() {
        $this->controllerName = get_class($this);
    }

    public function run($method, $params = array()) {
        if (!method_exists($this, $method)) {
            throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controllerName . '/' . $method);
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
            $baseUrl = $request->getBaseUrl();

            $url = $protocol . $host . $baseUrl . $url;
        }

        $statusCode = Di::get(StatusCode::class)->setCode(302)->setText('Found');
        $header = Di::get(HttpHeader::class)->setName('Location')->setValue($url);
        $httpHeaders = Di::get(HttpHeaders::class)->addHeader($header);
        Di::set(Response::class, Di::get(Response::class)->setStatusCode($statusCode)->setHttpHeaders($httpHeaders));
    }
}
