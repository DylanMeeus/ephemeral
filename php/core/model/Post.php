<?php

if(!defined("SERVLET"))
    die("You may not view this page");


class Post{


    private $id;
    private $userid;
    private $threadid;
    private $message;


    public function setId($id){$this->id = $id;}
    public function setUserId($userId){$this->userid = $userId;}
    public function setThreadId($threadId){$this->threadid = $threadId;}
    public function setMessage($message){$this->message = $message;}


    public function getId(){return $this->id;}
    public function getUserId(){return $this->userid;}
    public function getThreadId(){return $this->threadid;}
    public function getMessage(){return $this->message;}


}



?>