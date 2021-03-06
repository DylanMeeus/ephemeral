<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/factories/DatabaseFactory.php";
require_once "php/data/Slackdata.php";
require_once "php/data/Database.php";
require_once "php/model/Image.php";
require_once "php/core/model/JsonResponse.php";
require_once "php/factories/ImageFactory.php";

class Facade{

    private $database, $file, $jsonResponse;

    public function __construct(){

        $this->database = DatabaseFactory::create();
        $this->file = FileFactory::create();
        $this->jsonResponse = new JsonResponse();

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
            // post to slack
            DebugHelper::log("testing slackdata");
            $slackData = new SlackData();
            $slackData->postToSlack($message);
        }
        catch(Exception $ex)
        {
            DebugHelper::log($ex->getMessage());
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

    public function changePassword($username, $oldPassword, $newPassword){
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->database->changePassword($username, $oldPassword, $newPassword);
    }

    public function uploadProfilePicture($coordString, $imgSrc){
        return $this->file->uploadProfilePicture($coordString, $imgSrc);
    }

    public function updatePersonalMessage($username, $personalMessage){
        //return $this->database->updatePersonalMessage($username, $personalMessage);

    }

    public function updateFullAvatar($files){
        return $this->database->updateFullAvatar(
            $_SESSION["user"],
            $this->file->uploadFullAvatar($_SESSION["user"], $files)
        );
    }

    public function updateAvatar($coordString, $imgSrc){
        return $this->database->updateAvatar(
            $_SESSION["user"],
            $this->file->uploadAvatar(
                $_SESSION["user"], $coordString, $imgSrc
            )
        );
    }

    public function updateUser($user){
        return $this->database->updateUser($user);
    }

    public function generateResponse($success, $messages, $data){
        return $this->jsonResponse->generateResponse($success, $messages, $data);
    }
}