<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

/**
 *
 * This file contains path information for use throughout the website.
 *
 * This is for use in CSS / Javascripts includes etc. as the / pre-slash method defaults to the root directory,
 * which in a testing environment doesn't work (as localhost/ is not my root) - plus it's good practice anyway.
 */

// Default URL definition
$baseUrl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
$baseUrlParsed = parse_url($baseUrl);
define( "BASE_URL" , str_replace( "index.php", "", $baseUrlParsed["scheme"] . "://" . $baseUrlParsed["host"] . $baseUrlParsed["path"] ));

// URL definitions
define( "CSS_URL" , BASE_URL . "css/" );
define( "JS_URL" , BASE_URL . "js/" );
define( "FONTS_URL" , BASE_URL . "fonts/" );