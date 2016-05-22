<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";
require_once "php/factories/DatabaseFactory.php";
require_once "php/data/File.php";
require_once "php/factories/FileFactory.php";

class Facade{

    // Vars to contain the objects
    private $database;

    // Constructor will use static methods to obtain objects and turn the vars above in to said objects ... man I comment too much
    public function __construct(){

        $this->database = DatabaseFactory::create();
        $this->file = FileFactory::create();

    }

    public function registerAccount($username, $password, $email, $firstName, $lastName){
        // Hash the password
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

    public function changePassword($username, $oldPassword, $newPassword){
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->database->changePassword($username, $oldPassword, $newPassword);
    }

    public function uploadProfilePicture($coordString, $imgSrc){
        return $this->file->uploadProfilePicture($coordString, $imgSrc);
    }

    public function uploadImage($file){
        return $this->file->uploadImage($file);
    }

    public function updatePersonalMessage($username, $personalMessage){
        return $this->database->updatePersonalMessage($username, $personalMessage);
    }

}