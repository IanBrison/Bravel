<?php

namespace Core\Exception;

use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Presenter\View;
use Exception;
use Throwable;

class BravelExceptionHandler extends Exception implements BravelException {

    private $e;
    private $registeredExceptions;

	/**
	 * BravelExceptionHandler constructor.
	 * @param Throwable $e
	 * @throws Exception
	 */
	public function __construct(Throwable $e) {
        parent::__construct();
        $this->e = $e;
        $this->registeredExceptions = Environment::getConfig('exception.registeredExceptions');
    }

    public function handle($isDebugMode) {
        if (in_array(get_class($this->e), $this->registeredExceptions)) {
            return $this->e->handle($isDebugMode);
        }

        Di::get(StatusCode::class)->setCode(500)->setText('Internal Server Error');
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

        Di::get(View::class)->render('error/handler', ['main_message' => $main_message, 'message_stack' => $message_stack]);
    }
}
