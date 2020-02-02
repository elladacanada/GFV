<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

if( !isset( $_SESSION) ) session_start();
//manages inclusion of all controller and model files

// print_r($_COOKIE);
// Create a constant variable to hold the path to the root directory of the project

// $_SERVER["DOCUMENT_ROOT"];

// this is storing the root of our folder into a variable called APP_ROOT
define('APP_ROOT', substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR)) );
//substr("/Applications/MAMP/htdocs/semester 6/PLAPI/MVC_Template-model-view-controller", 0, "/controller")
//strrpos("/Applications/MAMP/htdocs/semester 6/PLAPI/MVC_Template-model-view-controller" "/" )

define('APP_NAME', 'Project Share');
define('APP_DEBUG', false); // can set this to false when no longer need it for debugging

// echo APP_ROOT;

require_once(APP_ROOT . "/controllers/db.php");
require_once(APP_ROOT . "/controllers/util.php");

// Automatically include all files in the /models folder
spl_autoload_register(function($class){
    // $class = Users
     // add any .php file extension with the call name to match, but must be lower case
    $filename = strtolower($class) . ".php";

    // Check if the class file esists and is in the model folder
    if( file_exists( APP_ROOT . '/models/' . $filename ) ){
        require_once( APP_ROOT . '/models/' . $filename );
    }

});

if(!empty($_COOKIE["user_logged_in"])) {
    $_SESSION["user_logged_in"] = $_COOKIE["user_logged_in"];
}

if(!empty($_SESSION["user_logged_in"])) {
    //user object
    $user = new User;
    // get_by_id is a method or function we are setting
    $current_user = $user->get_by_id( $_SESSION["user_logged_in"] );

           
}
?>