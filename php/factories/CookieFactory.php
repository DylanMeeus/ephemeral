<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/Cookie.php";

class CookieFactory{
    public static function create(){
        return new Cookie();
    }
}