<?php

if(!defined("SERVLET"))
    die("You may not view this page.");


if(isset($_SESSION["user"])){

    // First of all see if they have been sent via the login page
    if(strpos($_SERVER["REQUEST_URI"], "?action=loginaccount") !== false){
        // Comment this line out to enable debugging on that page
        header("Location: index.php?action=profile");
    }


    echo '<a href="index.php">Go Home</a><br />';

    echo $_SESSION["user"]->getFirstName();
    echo '<br /><a href="index.php?action=logout">Log Out</a>';

}
else // not logged in
{
    header("Location: index.php");
}