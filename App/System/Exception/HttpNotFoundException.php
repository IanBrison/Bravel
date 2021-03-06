<?php

namespace App\System\Exception;

use Core\Di\DiContainer as Di;
use Core\Exception\BravelException;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Presenter\View;
use Exception;

class HttpNotFoundException extends Exception implements BravelException {

	public function handle($isDebugMode) {
		$status_code = Di::get(StatusCode::class)->setCode(404)->setText('Not Found');
		$message = $isDebugMode ? $this->getMessage() : 'Page not found.';
		$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

		$content = Di::get(View::class)->render('error/404', ['message' => $message], null);
		Di::set(Response::class, Di::get(Response::class)->setStatusCode($status_code)->setContent($content));
	}
}
