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
            'dsn'      => getenv('DATABASE_DSN'),
            'user'     => getenv('DATABASE_USER'),
            'password' => getenv('DATABASE_PASSWORD'),
            'options'  => [],
        ],
    ]
];
