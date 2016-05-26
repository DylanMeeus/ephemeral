<?php
if(!defined("SERVLET"))
    die("You may not view this page.");
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="/ephemeral/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/ephemeral/css/custom.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="utf-8">
    <title>Ephemeral</title>
</head>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">ephemeral</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="<?php echo isset($_GET["action"]) ? "" : "active" ?>"><a href="index.php">Home</a></li>
                <li class="<?php  ?>"><a href="index.php?action=forum">Forum</a></li>
                <li class="<?php  ?>"><a href="index.php?action=forum">Shoutbox</a></li>
                <li class="<?php  ?>"><a href="index.php?action=forum">Members</a></li>
                <li class="<?php  ?>"><a href="index.php?action=help">Help</a></li>
                <?php

                if(isset($_SESSION["user"])){
                    ?>
                    <li class="dropdown <?php echo (@$_GET["action"] == "profile") ? "active" : "" ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?action=profile">View my Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Settings</li>
                            <li><a href="index.php?action=settings&settings=profile">Profile Settings</a></li>
                            <li><a href="index.php?action=settings&settings=account">Account Settings</a></li>
                        </ul>
                    </li>
                    <?php
                }
                ?>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php

                    if(isset($_SESSION["user"])){
                        $firstName = $_SESSION["user"]->getFirstName();
                        $lastName = $_SESSION["user"]->getLastName();
                ?>
                    <form class="navbar-form navbar-right" action="index.php?action=logout" method="post">
                        <div class="form-group">
                            <li class="text-white">Welcome, <?php echo "$firstName $lastName." ?>&nbsp;</li>
                        </div>
                        <button type="submit" class="btn btn-success">Log out</button>
                    </form>
                <?php
                    }else{
                ?>

                    <form class="navbar-form navbar-right" action="index.php?action=loginaccount" method="post">
                        <div class="form-group">
                            <input type="text" placeholder="Username" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form>

                    <?php
                }

                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>