<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title="Index";
 
// include login checker
$require_login=true;
include "login_checker.php";
 
// include page header HTML
include_once 'layout_head.php';
 
echo "<div class='col-md-12'>";
    // to prevent undefined index notice
    $action = isset($_GET['action']) ? $_GET['action'] : "";
 
    // if login was successful
    if($action=='login_success' || (isset($_SESSION['logged_in']) && $_SESSION['logged_in']==1))
    {
        if($_SESSION['type']==1) header("Location: {$home_url}admin/index.php?action=logged_in_as_admin");
        echo "<div class='alert alert-info'>";
            echo "<strong>Hi " . $_SESSION['name'] . "!</strong>";
        echo "</div>";

        if($action=='already_logged_in')
        {
            echo "<div class='alert alert-info'>";
                echo "<strong>You are already logged in!</strong>";
            echo "</div>";
        }

        echo "<div class='alert alert-info'>";
            echo "Open My Tests Tab to View Your Tests.";
        echo "</div>";

        echo "<div class='alert alert-info'>";
            echo "<a href='test_registration.php'>
                    <span class='glyphicon glyphicon-plus'></span>
                    <b> Register For Test</b>
                </a>";
        echo "</div>";
    }

    //not logged in 
    else
    {
        echo "<div class='alert alert-info'>";
            echo "<a href='login.php'><b>Login</b></a> or <a href='register.php'><b>Register</b></a> to continue.";
        echo "</div>";
    }
 
echo "</div>";
 
// footer HTML and JavaScript codes
include 'layout_foot.php';
?>