<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// Required files
require_once "php/data/DatabaseConnect.php";
require_once "php/factories/UserFactory.php";
require_once "php/factories/CookieFactory.php";

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

    public function setUser($userID){

        // Set an account from the user ID
        $userInfo = $this->getUserInfo(0, "userid", $userID);

        $user = UserFactory::create();
        $userInfo = $userInfo[0];
        $user->setUserID($userInfo["userid"]);
        $user->setEmail($userInfo["email"]);
        $user->setFirstName($userInfo["firstname"]);
        $user->setLastName($userInfo["lastname"]);
        $user->setSignature($userInfo["signature"]);
        $user->setPersonalMessage($userInfo["personalmessage"]);
        $user->setAvatar($userInfo["avatar"]);
        $user->setRoleID($userInfo["roleid"]);
        $user->setUsername($userInfo["username"]);

        return $user;
    }

    public function loginAccount($username, $password){

        // First off, see if a user with this username exists
        // Grab the password from the user if they do, so we can compare it with the entered one. 2 birds, one large rock.
        $userInfo = $this->getUserInfo(0, "username", $username);

        // If it is empty, then the user does not exist, so return that
        if(empty($userInfo)){
            return "user_not_found";
        }

        // If we get this far, we now have a hashed password to compare, so let's compare it
        if(password_verify($password, $userInfo[0]["password"])){
            // Password matches, so log the user in
            $user = $this->setUser($userInfo[0]["userid"]);

            // Return the user object
            return $user;
        }else{
            return "pass_wrong";
        }
    }

    public function getDBCookie($token, $cookieType){

        // Hashing is done in the Facade

        // Get the cookie type ID
        $cookieTypeID = $this->getCookieTypeID($cookieType);

        // If typeID is false, no type ID is set for this cookie yet
        if(!$cookieTypeID){
            return "no_cookie_id";
        }

        // See if the given cookie exists
        $sql = "SELECT * FROM cookies WHERE value = ? AND cookietypeid = ?";

        $this->dbConnect();

        $stmt = $this->con->prepare($sql);

        $stmt->bindParam(1, $token);
        $stmt->bindParam(2, $cookieTypeID);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        $this->dbDisconnect();

        // Return the cookie info in the form of an object
        $cookieInfo = $results[0];

        $cookie = CookieFactory::create();

        $cookie->setCookieID($cookieInfo["cookieid"]);
        $cookie->setUserID($cookieInfo["userid"]);
        $cookie->setCookieTypeID($cookieInfo["cookietypeid"]);
        $cookie->setValue($cookieInfo["value"]);

        return $cookie;
    }

    public function setDBCookie($token, $userID, $cookieType){

        // Token is hashed in the Facade
        // Get the cookie type ID
        $cookieTypeID = $this->getCookieTypeID($cookieType);

        // If typeID is false, no type ID is set for this cookie yet
        if(!$cookieTypeID){
            return "no_cookie_id";
        }

        // See if the user already has a cookie of this type
        $sql = "SELECT * FROM cookies WHERE userid = ? AND cookietypeid = ?";

        $this->dbConnect();

        $stmt = $this->con->prepare($sql);

        $stmt->bindParam(1, $userID);
        $stmt->bindParam(2, $cookieTypeID);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        // If there is an existing one, we just want to update it rather than creating a new one
        if(!empty($results)){

            // Update query rather than insert
            $sql = "
                UPDATE cookies
                SET value=?
                WHERE userid=? AND cookietypeid=?
            ";

            // Prepare
            $stmt = $this->con->prepare($sql);

            // Bind the params
            $stmt->bindParam(1, $token);
            $stmt->bindParam(2, $userID);
            $stmt->bindParam(3, $cookieTypeID);
        }else{

            // SQL query to insert the cookie info
            $sql = "
                INSERT INTO cookies(userid, cookietypeid, value)
                VALUES(?, ?, ?)
            ";

            // Prepare the stmt
            $stmt = $this->con->prepare($sql);

            // Bind the params
            $stmt->bindParam(1, $userID);
            $stmt->bindParam(2, $cookieTypeID);
            $stmt->bindParam(3, $token);
        }

        // Execute the query
        return $stmt->execute();
    }

    private function getCookieTypeID($cookieType){

        // SQL to get the cookie type ID
        $sql = "SELECT * FROM cookietypes WHERE cookietype = ?";

        // Connect to the DB
        $this->dbConnect();

        // Prepare the stmt
        $stmt = $this->con->prepare($sql);

        // Bind the param
        $stmt->bindParam(1, $cookieType);

        // Execute & fetch
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $results = $stmt->fetchAll();

        // Disconnect
        $this->dbDisconnect();

        // Return the ID
        return !empty($results) ? $results[0]["cookietypeid"] : false;
    }
}






















