<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/Facade.php";
require_once "php/config/path.php";

class Servlet{

    /**
     * @var facade
     * - Facade, will contain Facade object
     * - Set in constructor
     */
    private $facade;

    /**
     * @var data
     * - This is an array that will return data to pages
     */
    private $data, $urls, $errors, $notifications = array();

    public function __construct(){

        // Fire up a session right away
        session_start();

        // Again, no comment needed but hey, I'm giving you a comment: you smell awesome today.
        $this->facade = new Facade();

        // A default timezone, for database time consistency
        date_default_timezone_set("Europe/London"); // Stick this in a config file when you figure out a name for the file :p

        // Set up the urls array
        $this->urls["css"] = CSS_URL;
        $this->urls["js"] = JS_URL;
        $this->urls["fonts"] = FONTS_URL;
    }

    public function processRequest(){

        // If there is no user session
        if(!isset($_SESSION["user"])){

            $cookieName = "loggedin";

            // See if there is a cookie
            if(isset($_COOKIE[$cookieName])){

                // Check that the cookie matches in the Database
                $dbCookie = $this->facade->getDBCookie($_COOKIE[$cookieName], $cookieName);

                // Compare the 2 and set a session if it is approved
                if(sha1($_COOKIE[$cookieName]) === $dbCookie->getValue()){

                    // Set the session
                    $_SESSION["user"] = $this->facade->setUser($dbCookie->getUserID());

                    // Set a new cookie
                    $cookieValue = microtime();
                    setcookie($cookieName, $cookieValue, time() + 60 * 60 * 24 * 30);

                    // Set it in the database too
                    $this->facade->setDBCookie($cookieValue, $_SESSION["user"]->getUserID(), $cookieName);
                }
            }
        }

        // Page to load, set as nothing to begin with
        $loadPage = "";

        // Default action
        $action = "home";

        // Set up an action from post/get
        if(isset($_POST["action"]))
            $action = $_POST["action"];
        elseif(isset($_GET["action"]))
            $action = $_GET["action"];

        /**
         * @var redirect
         * - Redirection variable
         * - Will define whether or not the action's eventual method will just execute or reload the page as well
         * - example: if true, the method will be "require_once"d so the method will need to return a page to load
         * - example: if false, the method will just execute, and run the method without requiring it, useful for xml/json outputs etc.
         */
        $redirect = true;

        /**
         * @var header
         * @var footer
         * - These ones define whether or not we will include a header file and/or a footer file around the method
         */
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
            case "uploadprofilepicture":
                $redirect = $header = $footer = false;
                $this->uploadProfilePicture();
                break;
            case "uploadimage":
                $redirect = $header = $footer = false;
                $this->uploadImage();
                break;
            case "changepassword":
                $redirect = $header = $footer = false;
                $this->changePassword();
                break;
            case "getavatar":
                $redirect = $header = $footer = false;
                $this->getAvatar();
                break;
            case "changepersonalmessage":
                $redirect = $header = $footer = false;
                $this->setPersonalMessage();
                break;
            default:
                $loadPage = $this->home();
        }

        /**
         * Now we decide what to do with the data returned from the switch statement
         * Note: If the switch statement simply executed a method, $loadPage will still be set to "" so nothing will happen
         * Preceeding the require like with @ just in-case a mistake is made somewhere at any time - this way loading nothing will just happen without errors
         */

        if($header)
            require_once("pages/templates/header.php");

        if($redirect)
            @require_once("pages/$loadPage");

        if($footer)
            require_once("pages/templates/footer.php");
    }

    /**
     * Boring Page Methods
     */

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

    /**
     * Methods that actually do things
     */

    private function changePassword(){

        // If the form data is set
        if(isset($_POST["oldpassword"])){

            // Grab the post data
            $oldPassword = $_POST["oldpassword"];
            $newPassword = $_POST["newpassword"];
            $repeatNewPassword = $_POST["repeatnewpassword"];

            if($newPassword != $repeatNewPassword){
                echo "no_password_match";
                return true;
            }

            $result = $this->facade->changePassword($_SESSION["user"]->getUsername(), $oldPassword, $newPassword);

            echo $result;
        }else{
            return false;
        }
    }

    private function uploadProfilePicture(){

        // Grab the post data
        if(!empty($_POST["coordString"]) && !empty($_POST["imgSrc"])){

            // Get the POST data
            $coordString = $_POST["coordString"];
            $imgSrc = $_POST["imgSrc"];

            // Upload the profile picture (This also updates the avatar in the DB)
            $results = $this->facade->updateAvatar($coordString, $imgSrc);

            // Update the session to reflect the changes
            $this->updateSession();

            // Display the results for jQuery
            echo $results;
        }else{
            echo "Stuff was not filled in...";
        }
    }

    private function uploadImage(){
        // Make sure a file is there, then upload the image and display whatever is returned (should be an image resource)
        if(!empty($_FILES)){

            // Get the image name from the model (Also updates large image name in DB)
            $image = $this->facade->updateFullAvatar($_FILES);

            // Update the session to reflect the changes
            $this->updateSession();

            // Display the results for jQuery
            echo $image;
        }
    }

    private function registerAccount(){

        // Get stuff from the form
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $firstName = $_POST["firstname"];
        $lastName = $_POST["lastname"];

        // Insert the user's account
        $account = $this->facade->registerAccount($username, $password, $email, $firstName, $lastName);

        switch($account){
            case "invalid_email":
                $this->errors[] = "The e-mail address you entered is invalid.";
                break;
            case "user_exists":
                $this->errors[] = "A user with this username already exists.";
                break;
            case "insert_failed":
                $this->errors[] = "The insertion failed - See pseud.";
                break;
            case "user_created":
                $this->notifications[] = 'The User Account was created, you may now login.';
                break;
        }

        $loadPage = "registeraccount.php";
        return $loadPage;
    }

    private function loginAccount(){

        // Get the user / pass etc.
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

            // Set the session userinfo
            $_SESSION["user"] = $user;

            // Set a loggedin cookie if remember me was checked
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

        // Send the user to the homepage
        $nextPage = "home.php";
        return $nextPage;
    }

    /**
     * Method to return the user's Avatar through to Javascript
     */
    private function getAvatar(){

        // If the session is set
        if(isset($_SESSION["user"])){
            // Return it
            echo $_SESSION["user"]->getAvatar() . "-cropped.jpg";
        }
    }

    private function setPersonalMessage(){

        // Update the current user's personal message
        // Get the new message
        if(isset($_POST)){
            $personalMessage = $_POST["pm"];

            // Send it to the database!
            $result = $this->facade->updatePersonalMessage($_SESSION["user"]->getUsername(), $personalMessage);

            // If it worked
            if($result){

                // Update the session
                $_SESSION["user"]->setPersonalMessage($personalMessage);

                // Return success message for jQuery
                echo $_SESSION["user"]->getPersonalMessage();
            }else{
                echo 0;
            }
        }
    }

    /**
     * Methods to be called from within this file only
     */
    // This method is to update the entire session in the case of not having a specific return to put in the Session with a setter
    private function updateSession(){

        // Get the USER ID
        $userID = $this->facade->setUser($_SESSION["user"]->getUserID());

        // Update the current session with the new data
        $_SESSION["user"] = $userID;
    }
}