<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/User.php";

class UserFactory{
    public static function create(){
        return new User();
    }
}