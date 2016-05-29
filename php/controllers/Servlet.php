<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/Facade.php";
require_once "php/config/path.php";
require_once "php/helper/debughelper.php";
require_once "php/core/model/Shoutbox.php";
require_once "php/core/model/Shoutboxmessage.php";

class Servlet{

    /**
     * @var facade
     *  The facade is our Single-Point-Of-Access to the model
     */
    private $facade;

    /**
     * @var data
     * - This is an array that will return data to pages
     */
    private $data, $urls, $errors, $notifications = array();

    public function __construct(){
        $this->setup();
    }

    private function setup()
    {
        $this->facade = new Facade();
        date_default_timezone_set("Europe/London"); // Stick this in a config file when you figure out a name for the file :p

        $this->urls["css"] = CSS_URL;
        $this->urls["js"] = JS_URL;
        $this->urls["fonts"] = FONTS_URL;
    }

    public function processRequest()
    {

        session_start();
        if (!isset($_SESSION["user"])) {

            $cookieName = "loggedin";

            if (isset($_COOKIE[$cookieName])) {

                // Check that the cookie matches in the Database
                $dbCookie = $this->facade->getDBCookie($_COOKIE[$cookieName], $cookieName);
                if (sha1($_COOKIE[$cookieName]) === $dbCookie->getValue()) {

                    $_SESSION["user"] = $this->facade->setUser($dbCookie->getUserID());

                    // Set a new cookie & store it in the database
                    $cookieValue = microtime();
                    setcookie($cookieName, $cookieValue, time() + 60 * 60 * 24 * 30);

                    $this->facade->setDBCookie($cookieValue, $_SESSION["user"]->getUserID(), $cookieName);
                }
            }
        }


        // Page to load, set as nothing to begin with
        $loadPage = "";

        // Default action
        $action = "home";

        // Set up an action from post/get
        if (isset($_POST["action"]))
        {
            $action = $_POST["action"];
        }
        elseif (isset($_GET["action"]))
        {
            $action = $_GET["action"];
        }

        // stay on same page (and push data), or redirect
        $redirect = true;

        // define if we need headers/footers
        $header = true;
        $footer = true;


        // Note - All variables above should be changed inside the case, within the switch statement, if necessary
        // Switch the action variable that we obtained from the POST / GET (default is set above)
        switch($action){
            case "home":
                $loadPage = $this->home();
                break;
            case "register":
                $loadPage = $this->register();
                break;
            case "registeraccount":
                $loadPage = $this->registerAccount();
                break;
            case "login":
                $loadPage = $this->login();
                break;
            case "loginaccount":
                $loadPage = $this->loginAccount();
                break;
            case "profile":
                $loadPage = $this->profile();
                break;
            case "logout":
                $loadPage = $this->logout();
                break;
            case "gotoshoutbox": // just loads the page, does not push any data to it.
            {
                $loadPage = "shoutbox.php";
                break;
            }
            case "postshout" : // adds to database
            {
                $redirect = false;
                $this->postShout();
                break;
            }
            case "loadshouts":
            {
                $redirect = false;
                $this->loadShouts();
                break;
            }
            default:
                $loadPage = $this->home();
        }

        /**
         * Now we decide what to do with the data returned from the switch statement
         * Note: If the switch statement simply executed a method, $loadPage will still be set to "" so nothing will happen
         * Preceeding the require like with @ just in-case a mistake is made somewhere at any time - this way loading nothing will just happen without errors
         */

        if($redirect){

            // the order in which these get called is important.
            if($header){
                require_once("pages/templates/header.php");
            }


            @require_once("pages/$loadPage");


            if($footer){
                require_once("pages/templates/footer.php");
            }
        }

    }


    private function home(){
        $loadPage = "home.php";
        return $loadPage;
    }

    private function register(){
        $loadPage = "register.php";
        return $loadPage;
    }

    private function login(){
        $loadPage = "login.php";
        return $loadPage;
    }

    private function profile(){
        $loadPage = "profile.php";
        return $loadPage;
    }

    private function loadShouts()
    {
        $shoutbox = $this->facade->loadShoutbox();

        echo $shoutbox->toJsonString();

    }


    private function postShout()
    {
        $shoutMessage = $_POST['shout'];
        DebugHelper::log("shoutmessage " . $shoutMessage);
        try
        {
            $this->facade->postShoutboxMessage(2, $shoutMessage);
            echo "done";
        }
        catch(Exception $ex)
        {
            echo "exception";
        }
    }

    private function registerAccount(){

        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstname"];
        $lastName = $_POST["lastname"];

        // Insert the user's account
        DebugHelper::log("servlet: " . $email . "first: " . $firstName . "last: " . $lastName . "user: " .$username);

        $account = $this->facade->registerAccount($username, $password, $email, $firstName, $lastName);

        // if something went wrong, we want to redirect the user to the registration page. This way, they can just try again

        $loadPage = "registeraccount.php";
        switch($account){
            case "invalid_email":
                $this->errors[] = "The e-mail address you entered is invalid.";
                $loadPage = "register.php";
                break;
            case "user_exists":
                $this->errors[] = "A user with this username already exists.";
                $loadPage = "register.php"; // TODO: push the data back to the form to auto-populate that which was not wrong
                break;
            case "insert_failed":
                $this->errors[] = "The insertion failed - See pseud.";
                break;
            case "user_created":
                $this->notifications[] = 'The User Account was created, you may now login.';
                break;
        }

        return $loadPage;
    }

    private function loginAccount(){

        $username = $_POST["username"];
        $password = $_POST["password"];
        @$rememberMe = $_POST["rememberme"];

        $user = $this->facade->loginAccount($username, $password);

        switch($user){
            case "user_not_found":
                $this->errors[] = "A user with that username was not found. Please try again.";
                $loadPage = "login.php.php";
                return $loadPage;
            case "pass_wrong":
                $this->errors[] = "You entered an incorrect password. Please try again";
                $loadPage = "login.php";
                return $loadPage;
        }

        // Ensure that the user object is returned by checking that it is not null
        if($user != null) {

            $_SESSION["user"] = $user;

            if(isset($rememberMe)){
                $cookieName = "loggedin";
                $cookieValue = microtime();
                setcookie($cookieName, $cookieValue, time() + 60 * 60 * 24 * 30);

                // Set it in the database too
                $this->facade->setDBCookie($cookieValue, $user->getUserID(), $cookieName);
            }

            // Send the user to the profile page
            $loadPage = "profile.php";
            return $loadPage;
        }
    }

    private function logout(){

        // Log the user out if they are logged in
        if(isset($_SESSION["user"])){

            $cookieName = "loggedin";
            $cookieValue = "";

            // Comment out these 2 lines to debug / test cookies remembering you
            setcookie($cookieName, $cookieValue, time() - 3600);
            $this->facade->setDBCookie($cookieName, $_SESSION["user"]->getUserID(), "");

            session_destroy();
        }

        $nextPage = "home.php";
        return $nextPage;
    }
}