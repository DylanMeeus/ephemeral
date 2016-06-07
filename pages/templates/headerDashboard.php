<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

?>

<link rel="stylesheet" href="<?php echo $this->urls["css"] ?>dashboard.css" />
<link rel="stylesheet" href="<?php echo $this->urls["css"] ?>jcrop/jquery.Jcrop.min.css" />

<!-- HIDDEN VALUES FOR JAVASCRIPT, NOTHING SECURE HERE -->
<div class="hidden" id="_username" data-value="<?php echo isset($_GET["username"]) ? $_GET["username"] : @$_SESSION["user"]->getUsername(); ?>"></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar" id="dashboard-sidebar">
            <ul class="nav nav-sidebar">
                <li class="<?php echo $_GET["action"] == "profile" ? "active" : "" ?>">
                    <a href="#">Profile<?php echo $_GET["action"] == "profile" ? '<span class="sr-only">(current)</span>' : "" ?></a>
                </li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Example</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Example</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>