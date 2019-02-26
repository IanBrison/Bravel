<?php

/*
 * config file for bravel exceptions
 *
 * registeredExceptions is the exceptions that has original actions when been thrown
 */
return [
    'registeredExceptions' => [
        System\Exceptions\HttpNotFoundException::class,
        System\Exceptions\UnauthorizedActionException::class,
    ]
];
