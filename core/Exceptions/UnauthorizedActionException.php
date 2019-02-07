<?php

namespace Core\Exceptions;

use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Response\HttpHeader;
use Core\Response\HttpHeaders;

use \Exception;

class UnauthorizedActionException extends Exception implements BravelException {

    protected $loginUrl;

    public function handle($is_debub_mode = false) {
        if (!empty($this->loginUrl)) {
            $this->redirectToLoginUrl();
            return;
        }

        return 'unauthorized view';
    }

    private function redirectToLoginUrl() {
        $url = $this->loginUrl;
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

    public function setLoginUrl(String $loginUrl): UnauthorizedActionException {
        $this->loginUrl = $loginUrl;
        return $this;
    }
}
