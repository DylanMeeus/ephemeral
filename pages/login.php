<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// If the user is logged in
if(isset($_SESSION["user"]))
    header("Location: index.php?action=profile");

?>

<div class="container login-form">

    <form action="index.php?action=loginaccount" method="post" class="form-signin">

        <h2 class="form-signin-heading">Login Here</h2>

        <div class="form-group">

            <label for="username" class="sr-only">Username</label>
            <input type="text" id="username" name="username" class="form-control form-top-element" placeholder="Username" required autofocus>

            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" class="form-control form-bottom-element" placeholder="Password" required>

        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="rememberme" name="rememberme" id="rememberme"> Remember Me
            </label>
        </div>

        <input class="btn btn-lg btn-default btn-block" type="submit" value="Register">

    </form>

</div>