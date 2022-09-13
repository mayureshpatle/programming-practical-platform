<?php
    // core configuration
    include_once "config/core.php";

    $action = isset($_GET['action']) ? $_GET['action'] : "";

    if($action=="logout_confirmed")
    {
        // destroy session, it will remove ALL session settings
        session_destroy();
    
        //redirect to login page
        header("Location: {$home_url}login.php");
    }
    else
    {
        //redirect to home page, do not logout
        header("Location: {$home_url}");
    }
?>