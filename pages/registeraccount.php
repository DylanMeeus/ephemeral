<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

?>

    Errors:

<?php

foreach($this->errors as $error){
    echo "$error<br/>";
}

?>

    <br/>Notifications:

<?php

foreach($this->notifications as $notification){
    echo "$notification<br/>";
}

?>


<h1>Registeraccount.php</h1>
Login <a href="index.php?action=login">here.</a>
