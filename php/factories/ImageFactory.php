<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/model/Image.php";

class FileFactory{
    public static function create(){
        return new File();
    }
}