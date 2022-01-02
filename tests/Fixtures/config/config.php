<?php

return [
    'db' => [
        'connections' => [
            'db1' => [
                'driver' => 'mysql',
                'host' => $GLOBALS['MYSQL_HOST'],
                'database' => $GLOBALS['MYSQL_DB'],
                'username' => $GLOBALS['MYSQL_USER'],
                'password' => $GLOBALS['MYSQL_PASSWD'],
                'charset' => 'utf8', // Optional
                'timezone' => 'Europe/Berlin', // Optional
            ],

            'db2' => [
                'driver' => 'sqlite',
                'database' => $GLOBALS['SQLITE_DB'],
            ]
        ],

        'default_connection' => 'db2',
    ],
];
