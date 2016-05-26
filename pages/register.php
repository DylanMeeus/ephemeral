<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// If the user is logged in
if(isset($_SESSION["user"]))
    header("Location: index.php?action=profile");

?>

<!-- show some feedback from notifications / errors -->

<?php
    foreach($this->errors as $error){
        echo "$error";
    }
?>

<div class="container register-form">

    <form action="index.php?action=registeraccount" method="post" class="form-signin" >

        <h2 class="form-signin-heading">Register Here</h2>

        <div class="form-group">

            <label for="username" class="sr-only">Username</label>
            <input type="text" id="username" name="username" class="form-control form-top-element" placeholder="Username" required autofocus>

            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" class="form-control form-bottom-element" placeholder="Password" required>

        </div>

        <div class="form-group">

            <label for="email" class="sr-only">E-mail Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="E-mail Address" required>

        </div>

        <div class="form-group">

            <label for="firstname" class="sr-only">First Name</label>
            <input type="text" id="firstname" name="firstname" class="form-control form-top-element" placeholder="First Name" required>

            <label for="lastname" class="sr-only">Last Name</label>
            <input type="text" id="lastname" name="lastname" class="form-control form-bottom-element" placeholder="Last Name">

        </div>

        <input class="btn btn-lg btn-default btn-block" type="submit" value="Register">

    </form>

</div>