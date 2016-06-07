<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// If any action is set, remove it to bypass things not loading on refresh etc.
if(isset($_GET["action"])){
    header("Location: index.php");
}

?>

<h3>This is the home page.</h3>

<a href="index.php?action=register">Register Here</a>
<br />
<a href="index.php?action=login">Login here</a>

<?php

