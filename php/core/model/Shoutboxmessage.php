<?php


class ShoutboxMessage
{

    private $user;
    private $message;

    public function __construct()
    {

    }

    public function getUser(){return $this->user;}
    public function getMessage(){return $this->message;}

    public function setUser($user){$this->user=$user;}
    public function setMessage($message){$this->message = $message;}

}



?>