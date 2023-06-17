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
| API SECRET_KEY Configuration
|-----------------------------------------------------------------------
| Enter your RECAPTCHA_SECRET_KEY for your Google reCAPTCHA.
| Enter your VONAGE_API_KEY for your Vonage SMS API.
| Enter your VONAGE_API_SECRET for your Vonage SMS API.
|
*/

const RECAPTCHA_SECRET_KEY = 'YOUR_RECAPTCHA_SECRET_KEY';
const VONAGE_API_KEY = 'YOUR_VONAGE_API_KEY';
const VONAGE_API_SECRET = 'YOUR_VONAGE_API_SECRET';


