<?php

/*
|-----------------------------------------------------------------------
| Database Configuration
|-----------------------------------------------------------------------
| Enter the configuration for your MySQL Database
|
|    host    ->  your config host
|    user    ->  your config username
|    pass    ->  your config password
|    dbname  ->  your config name
|
*/

$config = [
    'host'   => 'localhost',
    'user'   => 'root',
    'pass'   => '',
    'dbname' => 'seat-n-savor'
];

$conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['dbname']);

if($conn->connect_error) {
    die(json_encode([
        'error' => $conn->connect_error
    ]));
}

/*
|-----------------------------------------------------------------------
| API Configuration
|-----------------------------------------------------------------------
| Enter your SECRET_KEY for your Google reCAPTCHA.
|
|    SECRET_KEY    ->  your SECRET_KEY
|
*/

const SECRET_KEY = 'SECRET_KEY';
