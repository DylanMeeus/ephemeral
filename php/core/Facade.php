<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/Database.php";
require_once "php/factories/DatabaseFactory.php";

class Facade{

    // Vars to contain the objects
    private $database;

    // Constructor will use static methods to obtain objects and turn the vars above in to said objects ... man I comment too much
    public function __construct(){

        $this->database = DatabaseFactory::create();

    }

    public function testDB(){
        return $this->database->testDB();
    }
}