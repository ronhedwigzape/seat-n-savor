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


    /***************************************************************************
     * Removes any special characters inside a phone number
     *
     * @param $phoneNumber
     * @return string
     */
    public function cleanPhoneNumber($phoneNumber): string
    {
        // Remove whitespaces, punctuation, symbols, and dashes
        $phoneNumber = preg_replace('/[\s\p{P}\p{S}-]+/', '', $phoneNumber);

        // Remove any words
        return preg_replace('/\p{L}+/u', '', $phoneNumber);
    }

}