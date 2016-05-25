<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/config/database.php";

class DatabaseConnect{

    // Protected vars for DB info - taken from definitions in the config file
    protected $host, $user, $pass, $dbname, $con;

    public function __construct(){
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->dbname = DB_DATABASE;
    }

    // Method to connect to the database
    public function dbConnect(){
        try{
            $this->con = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $exception){
            DebugHelper::log("Exception, could not connect to the database " . $exception->getMessage());
        }
    }

    // Method to disconnect from the database
    public function dbDisconnect(){
        $this->con = null;
    }
}