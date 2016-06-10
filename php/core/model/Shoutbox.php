<?php

require_once "Shoutboxmessage.php";

if(!defined("SERVLET"))
    die("You may not view this page.");



// not standard ORM
// keeps a map of shoutbox entries (array)
// allow for some pagination.
class Shoutbox
{
    // we store a bunch of messages
    // A message contains a user, and content


    // store the current count of everything in the database.
    // upon a new request, check this against the old count.
    private $messages = array();

    public function addMessage(ShoutboxMessage $shoutBoxMessage)
    {
        array_push($this->messages,$shoutBoxMessage);
    }

    public function getMessages()
    {
        return $this->messages;
    }


    public function getCount()
    {
        return count($this->messages);
    }

    public function toJsonString()
    {
        // returns the json string
        return (json_encode($this->messages));
    }

}


?>