<?php

namespace Core\Exceptions;

use Core\Di\DiContainer as Di;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\View\View;

use \Exception;

class HttpNotFoundException extends Exception implements BravelException {

    public function handle($is_debub_mode = false) {
        $status_code = Di::get(StatusCode::class)->setCode(404)->setText('Not Found');
        $message = $is_debub_mode ? $this->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $content = Di::get(View::class)->render('error/404', ['message' => $message], null);
        Di::set(Response::class, Di::get(Response::class)->setStatusCode($status_code)->setContent($content));
    }
}
