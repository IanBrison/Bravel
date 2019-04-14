<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Response\StatusCode;
use Core\Response\HttpHeader;
use Core\Response\HttpHeaders;
use Core\Response\Response;

class Url {

	public function redirect(string $url) {
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