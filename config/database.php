<?php

/*
 * config file for setting the database connection
 * all configurations go into a PDO instance
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
