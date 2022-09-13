<?php
    // core configuration
    include_once "config/core.php";

    $page_title = "Verify";
    include "login_checker.php";
    
    // include classes
    include_once 'config/database.php';
    include_once 'objects/user.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize objects
    $user = new User($db);
    
    // set access code
    $user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
    $user->id=isset($_GET['id']) ? $_GET['id'] : "";
    
    // verify if access code exists
    if(!$user->verifyAccessCode()){
        die("ERROR: Wrong Access Code or Account is already verified.");
    }
    
    // redirect to login
    else{
        // redirect
        header("Location: {$home_url}login.php?action=user_verified");
    }
?>