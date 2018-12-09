<?php

namespace Core\Exceptions;

use Core\Di\DiContainer as Di;
use Core\Response\Response;
use Core\Response\StatusCode;

class UnexpectedException extends \Exception implements BravelException {

    protected $e;

    public function __construct() {
        parent::__construct();
        $this->e = new \Exception();
    }

    public function render($is_debub_mode = false) {
        $status_code = Di::get(StatusCode::class)->setCode(500)->setText('Internal Server Error');
        $main_message = "{$this->e->getMessage()} in {$this->e->getfile()} line {$this->e->getLine()} code {$this->e->getCode()}";
        $message_stack = array();
        foreach ($this->e->getTrace() as $trace) {
            $file = $trace['file'] ?? '';
            $function = $trace['function'] ?? '';
            $line = $trace['line'] ?? '';
            $message = "file:$file, function:$function, line:$line";
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            $message_stack[] = $message;
        }
        $trace_error_in_string = implode("<br>", $message_stack);

        $content = <<< EOF
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>500</title>
</head>
<body>
    Something Unexpected Occured.
    <br>
    MainMessage: {$main_message}
    <br>
    StackTrace
    <br>
    {$trace_error_in_string}
</body>
</html>
EOF;
        Di::set(Response::class, Di::get(Response::class)->setStatusCode($status_code)->setContent($content));
    }

    public function setException(\Throwable $e): UnexpectedException {
        $this->e = $e;
        return $this;
    }
}
