<?php

require_once "./Shoutboxmessage.php";

if(!defined("SERVLET"))
    die("You may not view this page.");



// not standard ORM
// keeps a map of shoutbox entries (array)
// allow for some pagination.
class Shoutbox
{
    // we store a bunch of messages
    // A message contains a user, and content


    private $count; // current count of the shoutbox.
    private $messages = array();

    public function addMessage(ShoutboxMessage $shoutBoxMessage)
    {
        array_push($messages,$shoutBoxMessage);
    }

}


?>