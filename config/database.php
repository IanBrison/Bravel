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
            'dsn'      => 'mysql:dbname=braveldb;host=mariadb',
            'user'     => 'braveluser',
            'password' => 'bravelpassword',
            'options'  => [],
        ],
    ]
];
