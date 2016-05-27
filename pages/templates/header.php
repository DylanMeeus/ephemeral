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

<nav class="navbar navbar-inverse">
  <div class="container-fluid">    
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Ephemeral</a>
    </div>

    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
	<li><a href="#">Home</a></li>
	<li><a href="#">Forum</a></li>
	<li><a href="index.php?action=gotoshoutbox">Shoutbox</a></li> <!-- seperate shoutbox window, easier for phones -->
	<li><a href="#">Members</a></li> <!-- list all members -->
      </ul>
    </div>
  </div>
</nav>
