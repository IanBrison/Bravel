<?php
/*
 * config file for di container
 * singletons are instances that are to be saved when they are constructed
 * aliases are for choosing the right classes to use for construction
 */
return [
    'singletons' => [
        Core\Request\Request::class,
        Core\Response\Response::class,
        Core\Session\Session::class,
        Core\Database\DbManager::class,
        Core\Routing\Router::class,
    ],

    'aliases' => [
    ]
];
