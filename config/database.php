<?php

/* 
 * config file for setting the database connection
 * all configurations go in to a PDO instance
 */
return [
    'master' => [
        'dsn'      => 'mysql:dbname=braveldb;host=mariadb',
        'user'     => 'braveluser',
        'password' => 'bravelpassword',
    ],
];
