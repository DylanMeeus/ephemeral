<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// Required files
require_once "php/data/DatabaseConnect.php";

class Database extends DatabaseConnect{

    // Constructor from parent
    public function __construct(){
        parent::__construct();
    }

    // Test DB method
    public function testDB(){

        $this->dbConnect();

        $sql = "SELECT * FROM test";

        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        $this->dbDisconnect();

        return $results;
    }
}