<?php

namespace Core\Exceptions;

use Core\Di\DiContainer as Di;
use Core\Response\Response;
use Core\Response\StatusCode;

class HttpNotFoundException extends \Exception implements BravelException {

    public function render($is_debub_mode = false) {
        $status_code = Di::get(StatusCode::class)->setCode(404)->setText('Not Found');
        $message = $is_debub_mode ? $this->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $content = <<< EOF
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
EOF;
        Di::set(Response::class, Di::get(Response::class)->setStatusCode($status_code)->setContent($content));
    }

    public function setMessage(String $message): HttpNotFoundException {
        return new self($message);
    }
}
