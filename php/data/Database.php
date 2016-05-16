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

    public function getUserInfo($returns, $column, $value){

        // String of things we want to get from the database
        // If $returns is 0, we pull everything
        if(!$returns){
            $select = "*";

        // Otherwise, it contains something
        }else {

            // If it is an array...
            if (is_array($returns)) {

                // Implode the array in to a string, delimited with commas and surrounded with parentheses.
                $select = "(" . implode(", ", $returns) . ")";
            } else {
                $select = "($returns)";
            }
        }

        // Set up the sql query
        $sql = "SELECT $select FROM users WHERE $column = ?";

        // Connect to the database
        $this->dbConnect();

        // Prepare, bind and execute the query
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(1, $value);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Collect the results
        $results = $stmt->fetchAll();

        // Disconnect from the DB
        $this->dbDisconnect();

        // Return the results
        return($results);
    }

    public function registerAccount($username, $password, $email, $firstName, $lastName){

        // Ensure that the email is an actual e-mail address
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "invalid_email";
        }

        // See if a user with this username already exists
        $oldUser = $this->getUserInfo("userid", "email", $email);
        if(!empty($oldUser)){
            return "user_exists";
        }

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Set up an SQL to input the user to the DB
        $sql = "
            INSERT INTO users (username, password, email, firstname, lastname)
            VALUES(?,?,?,?,?)
        ";

        // Connect to the DB
        $this->dbConnect();

        // Prepare the statement
        $stmt = $this->con->prepare($sql);

        // Bind the params
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $firstName);
        $stmt->bindParam(5, $lastName);

        // Execute the query
        if(!$stmt->execute()){
            $this->dbDisconnect();
            return "insert_failed";
        }

        // Disconnect from the DB
        $this->dbDisconnect();

        // Return success string
        return "user_created";
    }

}