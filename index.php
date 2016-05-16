<?php

// Definition to ensure that this page can be loaded
define( "SERVLET", 1 );

include "php/controllers/Servlet.php";
$servlet = new Servlet();
$servlet->processRequest();