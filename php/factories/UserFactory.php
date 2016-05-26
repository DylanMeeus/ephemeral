<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/User.php";

class UserFactory{
    //TODO: probably will never require a factory, but it depends on how we implement the roles.
    public static function create(){
        return new User();
    }
}