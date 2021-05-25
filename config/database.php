<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('default','mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by PHP PDO is shown below to make development simple.
    |
    |
    | All database work in here is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
    'connections' => [
        'mysql'=>[
            // Name of database driver or IConnectionAdapter class
            'driver'    => env('driver','mysql'),
            'host'      => env('host','localhost'),
            'port'      =>env('port','3306'), 
            'database'  => env('database',''),
            'dbuser'  => env('dbuser','root'),
            'dbpassword'  => env('dbpassword',''),
            // Optional
            'charset'   => env('charset','utf8'),
        
            // Optional
            'collation' => env('collation','utf8_unicode_ci'),
        
            // Table prefix, optional
            'prefix'    => env('prefix',''),
        
            // PDO constructor options, optional
            'options'   => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
            ],
        ]
    ],
];