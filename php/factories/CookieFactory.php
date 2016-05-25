<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/Cookie.php";

class CookieFactory{
    // TODO: remove this, cookies do not require a factory.
    public static function create(){
        return new Cookie();
    }
}