<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";

class DatabaseFactory{
    public static function create(){
        return new Database();
    }
}