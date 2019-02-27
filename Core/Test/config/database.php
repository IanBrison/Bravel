<?php

/*
 * config file for setting the database connections
 *
 * all connection configurations go into PDO instances
 */
return [
    'options' => [
        'default' => 'master',
    ],

    'connections' => [
        'master' => [
            'dsn'      => '',
            'user'     => '',
            'password' => '',
            'options'  => [],
        ],
    ]
];
