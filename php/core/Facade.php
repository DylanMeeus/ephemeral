<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";
require_once "php/factories/DatabaseFactory.php";

class Facade{

    private $database;

    public function __construct(){
        $this->database = DatabaseFactory::create();
    }

    public function registerAccount($username, $password, $email, $firstName, $lastName){
        $password = password_hash($password, PASSWORD_DEFAULT);
        return $this->database->registerAccount($username, $password, $email, $firstName, $lastName);
    }

    public function loginAccount($username, $password){
        return $this->database->loginAccount($username, $password);
    }

    public function setDBCookie($token, $userID, $cookieType){
        // Hash the token
        $token = sha1($token);
        return $this->database->setDBCookie($token, $userID, $cookieType);
    }

    public function getDBCookie($token, $cookieType){
        $token = sha1($token);
        return $this->database->getDBCookie($token, $cookieType);
    }

    public function setUser($userID){
        return $this->database->setUser($userID);
    }

    // throws exception on error on insert
    public function postShoutboxMessage($userID, $message)
    {
        try
        {
            $this->database->postShoutboxMessage($userID,$message);
        }
        catch(Exception $ex)
        {
            throw new Exception("something went wrong: " . $ex->getMessage());
        }
    }

    public function loadShoutbox()
    {
        try
        {
            return $this->database->loadShoutbox(); // TODO: choose a better name for 'loadShoutbox', surround with try-catch
        }
        catch(Exception $ex)
        {
            DebugHelper::log("exception: " . $ex->getMessage());
            echo $ex->getMessage();
        }
    }

}