<?php
    // core configuration
    include_once "../config/core.php";
    
    // set page title
    $page_title = "Create Problem";
    
    // include login checker
    include_once "login_checker.php";
    
    // include classes
    include_once '../config/database.php';
    include_once '../objects/problem.php';
    include_once '../libs/php/utils.php';

    // include page header HTML
    include_once 'layout_head.php';

    echo "<div class='col-md-12'>";

    $created = false;    
    
    // if form was posted
    if($_POST)
    {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
    
        // initialize objects
        $problem = new Problem($db);

        $_POST['pcode'] = strtoupper($_POST['pcode']);
        $problem->pcode = $_POST['pcode'];
    
        // check if pcode already exists
        if($problem->pcodePresent())
        {
            echo "<div class='alert alert-danger'>";
                echo "This problem code is already used for another problem.";
            echo "</div>";
        }
    
        else
        {
            $problem->pname = $_POST['pname'];
            $problem->status = 0;

            $problem->utils = new Utils;

            if($problem->create())
            {
                echo "<div class='alert alert-info'>";
                    echo "<h4><b>{$problem->pname} ({$problem->pcode}) Created Successfully.</b></h4><br>";
                    echo "Complete further steps to make the problem ready.";
                echo "</div>";

                $arrow = "<span class='glyphicon glyphicon-circle-arrow-right'></span>";
                $problem_link = "{$home_url}admin/problem/details.php?pcode={$problem->pcode}";

                //Test Details Link
                echo "<div class='alert alert-info'>";
                    echo "<h4><b><a href={$problem_link}>Proceed Further {$arrow}</a></b></h4>";
                echo "</div>";

                $created = true;

                //empty posted values
                $_POST=array();
            }
            else
            {
                echo "<div class='alert alert-danger' role='alert'>Unable to Create Test. Please try again.</div>";
            }
        }
    }

    if($created == false)
    {
        include "problem_form.php";
    }

    echo "</div>";
    
    // include page footer HTML
    include_once "layout_foot.php";
?>