<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
include_once "login_checker.php";
 
// set page title
$page_title="Home";
 
// include page header HTML
include 'layout_head.php';
 
    echo "<div class='col-md-12'>";
 
        // get parameter values, and to prevent undefined index notice
        $action = isset($_GET['action']) ? $_GET['action'] : "";
 
        // tell the user he's already logged in
        if($action=='already_logged_in'){
            echo "<div class='alert alert-info'>";
                echo "<strong>You</strong> are already logged in.";
            echo "</div>";
        }
 
        else if($action=='logged_in_as_admin'){
            echo "<div class='alert alert-info'>";
                echo "<strong>You</strong> are logged in as Teacher. <br> Admin Privileges are Enabled!";
            echo "</div>";
        }

        $plus = "<span class='glyphicon glyphicon-plus'></span>";

        echo "<div class='row'>
                <div class='col-md-3'>
                    <p><a href='create_problem.php' class='btn btn-primary btn-lg btn-block' role='button'>
                        <b>{$plus} Create New Problem</b>
                    </a></p>
                </div>
                <div class='col-md-3'>
                    <p><a href='create_test.php' class='btn btn-primary btn-lg btn-block' role='button'>
                        <b>{$plus} Create New Test</b>
                    </a></p>
                </div>
            </div>";
 
    echo "</div>";
 
// include page footer HTML
include_once 'layout_foot.php';
?>