<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// If the user is logged in
if(isset($_SESSION["user"])){

    // First of all see if they have been sent via the login page
    // So if loginaccount is in the URL
    if(strpos($_SERVER["REQUEST_URI"], "?action=loginaccount") !== false){
        // Then redirect them to the actual profile page
        // Comment this line out to enable debugging on that page
        header("Location: index.php?action=profile");
    }

    // Link to home page
    echo '<a href="index.php">Go Home</a><br />';

    // Show their first name and a logout button
    echo $_SESSION["user"]->getFirstName();
    echo '<br /><a href="index.php?action=logout">Log Out</a>';

// Otherwise, the user is not logged in, so send them to the homepage
}else{
    header("Location: index.php");
}