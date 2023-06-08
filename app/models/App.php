<?php

date_default_timezone_set('Asia/Manila');

class App
{
    protected mixed $conn;


    /***************************************************************************
     *  App constructor
     */
    public function __construct()
    {
        if(isset($GLOBALS['conn']))
            $this->conn = $GLOBALS['conn'];
        else
            $this::returnError('HTTP/1.1 500', 'Missing database connection configuration.');
    }


    /***************************************************************************
     * Return an http error
     *
     * @param $header
     * @param $error
     * @return void
     */
    public static function returnError($header, $error): void
    {
        header($header);
        die(json_encode([
            'error' => $error
        ]));
    }

}