<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

require_once "php/core/Facade.php";

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
    private $data = array();

    public function __construct(){

        // Again, no comment needed but hey, I'm giving you a comment: you smell awesome today.
        $this->facade = new Facade();

        // A default timezone, for database time consistency
        date_default_timezone_set("Europe/London"); // Stick this in a config file when you figure out a name for the file :p
    }

    public function processRequest(){


        // Fire up a session right away
        session_start();

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
            // Another default, just for consistency and avoiding any potential errors ( I don't know how they'd happen but ... this is coding! )
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

    private function home(){

        // Test the DB and set the data array to whatever is returned
        $this->data = $this->facade->testDB();

        // Then load the page
        $loadPage = "home.php";
        return $loadPage;
    }
}