<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";
require_once "php/factories/DatabaseFactory.php";

class Facade{

    // Vars to contain the objects
    private $database;

    // Constructor will use static methods to obtain objects and turn the vars above in to said objects ... man I comment too much
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

}