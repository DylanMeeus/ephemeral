<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/data/DatabaseConnect.php";
require_once "php/factories/UserFactory.php";
require_once "php/factories/CookieFactory.php";
require_once "php/helper/debughelper.php";

require_once "php/core/model/Shoutboxmessage.php";
require_once "php/core/model/Shoutbox.php";

class Database extends DatabaseConnect{

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

            if (is_array($returns)) {

                // Implode the array in to a string, delimited with commas and surrounded with parentheses.
                $select = "(" . implode(", ", $returns) . ")";
            } else {
                $select = "($returns)";
            }
        }

        $sql = "SELECT $select FROM users WHERE $column = ?;";

        $this->dbConnect();

        // Prepare, bind and execute the query
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(1, $value);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        $this->dbDisconnect();

        return($results);
    }

    public function registerAccount($username, $password, $email, $firstName, $lastName)
    {

        // Ensure that the email is an actual e-mail address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            DebugHelper::log("invalid email: " .$email);
            return "invalid_email";
        }

        // See if a user with this username already exists
        $oldUser = $this->getUserInfo("userid", "email", $email);
        if (!empty($oldUser)) {
            return "user_exists";
        }

        $sql = "INSERT INTO users (username, password, email, firstname, lastname)VALUES(?,?,?,?,?);";

        try {
            $this->dbConnect();
            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $firstName);
            $stmt->bindParam(5, $lastName);

            // Execute the query
            if (!$stmt->execute()) {
                $this->dbDisconnect();
                return "insert_failed";
            }

            $this->dbDisconnect();
        }
        catch(Exception $e){
            DebugHelper::log($e->getMessage());
            $this->dbDisconnect();
            return "insert_failed";
        }

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

        if(empty($userInfo)){
            return "user_not_found";
        }

        // If we get this far, we now have a hashed password to compare, so let's compare it
        if(password_verify($password, $userInfo[0]["password"])){
            $user = $this->setUser($userInfo[0]["userid"]);
            return $user;
        }else{
            return "pass_wrong";
        }
    }

    public function getDBCookie($token, $cookieType){


        // Get the cookie type ID
        $cookieTypeID = $this->getCookieTypeID($cookieType);

        if(!$cookieTypeID){
            return "no_cookie_id";
        }

        // See if the given cookie exists
        $sql = "SELECT * FROM cookies WHERE value = ? AND cookietypeid = ?;";

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

        $cookieTypeID = $this->getCookieTypeID($cookieType);

        if(!$cookieTypeID){
            return "no_cookie_id";
        }

        // See if the user already has a cookie of this type
        $sql = "SELECT * FROM cookies WHERE userid = ? AND cookietypeid = ?;";

        $this->dbConnect();

        $stmt = $this->con->prepare($sql);

        $stmt->bindParam(1, $userID);
        $stmt->bindParam(2, $cookieTypeID);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        // If there is an existing one, we just want to update it rather than creating a new one
        if(!empty($results)){

            $sql = "
                UPDATE cookies
                SET value=?
                WHERE userid=? AND cookietypeid=?;
            ";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $token);
            $stmt->bindParam(2, $userID);
            $stmt->bindParam(3, $cookieTypeID);
        }else{

            $sql = "
                INSERT INTO cookies(userid, cookietypeid, value)
                VALUES(?, ?, ?);
            ";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $userID);
            $stmt->bindParam(2, $cookieTypeID);
            $stmt->bindParam(3, $token);
        }

        return $stmt->execute();
    }

    private function getCookieTypeID($cookieType){

        $sql = "SELECT * FROM cookietypes WHERE cookietype = ?;";

        $this->dbConnect();

        $stmt = $this->con->prepare($sql);

        $stmt->bindParam(1, $cookieType);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $results = $stmt->fetchAll();

        $this->dbDisconnect();

        // Return the ID
        return !empty($results) ? $results[0]["cookietypeid"] : false;
    }


    public function postShoutboxMessage($userID, $message)
    {
        /*  Possibly filter for "message length"
            Might make a "restrictions / validations class for this
        */
        try
        {
            $this->dbConnect();
            $sql = "insert into shoutbox(userid, message) values (?,?);";
            $statement = $this->con->prepare($sql);

            $statement->bindParam(1,$userID);
            $statement->bindParam(2,$message);

            $statement->execute();
        }
        catch(Exception $ex)
        {
            DebugHelper::log($ex->getMessage());
            throw new Exception("Posting to the shoutbox went wrong: " . $ex->getMessage());
        }
        finally
        {
            $this->dbDisconnect();
        }
    }

    /**
     * Start with a basic method to load the shoutbox. We don't need *that* much functionality to start with.
     */
    public function loadShoutbox()
    {
        $shoutBox = new Shoutbox();
        try
        {

            $this->dbConnect();


            $sql = "SELECT * FROM shoutbox";
            $sqlCount = "select count(messages) from shoutbox;";

            $statement = $this->con->prepare($sql);

            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $results = $statement->fetchAll();

            foreach($results as $res)
            {
                $userID = $res['userid'];
                $user = $this->getUserById($userID); // if the method didn't find a user, it will throw an error
                $message = $res['message'];         // We do not need to check if "user" is null, the error will stop execution.
                $shoutMessage = new ShoutboxMessage();
                $shoutMessage->setUser($user);
                $shoutMessage->setMessage($message);
                $shoutBox->addMessage($shoutMessage);
            }
        }
        catch(PDOException $pdoEx)
        {
            DebugHelper::log("pdo exception: " . $pdoEx->getMessage());
            echo $pdoEx->getMessage();
        }
        catch(Exception $ex)
        {
            DebugHelper::log("in catch: " . $ex->getMessage());
            echo $ex->getMessage(); // also echo it, in case we are not in production. But this is a doubtful practise.
        }
        finally
        {
            $this->dbDisconnect();
        }
        return $shoutBox;
    }


    /**
     * Considering we work with Ids, it makes sense to have a private method
     * that fetches the user information by ID.
     */
    private function getUserById($id) // returns a 'User' object.
    {

        try
        {
            $this->dbConnect();
            $sql = "select * from users where userid = ?";
            $statement = $this->con->prepare($sql);

            $statement->bindParam(1,$id);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $results = $statement->fetchAll();

            // this will only return 1 result, userIDs are unique. Enforcement by the database. (or no result)

            if(!empty($results)) {
                // fill the user with this information
                $result = $results[0];
                $user = new User();
                //TODO: we need a method for this, as we use it more often.
                // TODO: Create a builder as well?
                $user->setUserID($id);
                $user->setFirstName($result['firstname']);
                $user->setLastName($result['lastname']);
                $user->setUsername($result['username']);
                $user->setEmail($result['email']);
                $user->setAvatar($result['email']);
                $user->setRoleID($result['roleid']); // this needs to be replaced with an actual role.
                $user->setSignature($result['signature']);
                // we have set the user.
                $this->dbDisconnect();
                return $user;
            }
            throw new Exception("user not found");

        }
        catch(PDOException $ex)
        {
            DebugHelper::log($ex->getMessage());
            echo $ex->getMessage();
        }
        catch(Exception $ex)
        {
            DebugHelper::log($ex->getMessage());
            echo $ex->getMessage();
        }
        finally
        {
            $this->dbDisconnect();
        }
    }
}






















