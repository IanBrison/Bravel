<?php

/* 
 * config file for setting the database connection
 * all configurations go into a PDO instance
 */
return [
    'master' => [
        'dsn'      => 'mysql:dbname=braveldb;host=mariadb',
        'user'     => 'braveluser',
        'password' => 'bravelpassword',
    ],
];
