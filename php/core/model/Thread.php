<?php


if(!defined("SERVLET"))
    die("You may not view this page.");


class Thread{


    private $threadid;
    private $subject;
    private $ownerid;
    private $categoryid;


    public function getThreadId(){return $this->threadid;}
    public function getSubject(){return $this->subject;}
    public function getOwnerId(){return $this->ownerid;}
    public function getCategoryId(){return $this->categoryid;}


    public function setThreadId($id){$this->threadid = $id;}
    public function setSubject($subject){$this->subject = $subject;}
    public function setOwnerId($owner){$this->ownerid = $owner;}
    public function setCategoryId($cat){$this->categoryid = $cat;}


}




?>