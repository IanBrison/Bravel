<?php

/*
 * config file for bravel exceptions
 *
 * registeredExceptions is the exceptions that has original actions when been thrown
 */
return [
    'registeredExceptions' => [
        App\System\Exception\HttpNotFoundException::class,
        App\System\Exception\UnauthorizedActionException::class,
    ]
];
