<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";

class DatabaseFactory{

    // TODO: technically, it would take a parameter to say which kind of database we want to create.
    public static function create(){
        return new Database();
    }
}