<?php
    // core configuration
    include_once "../config/core.php";
    
    // set page title
    $page_title = "Create Test";
    
    // include login checker
    include_once "login_checker.php";
    
    // include classes
    include_once '../config/database.php';
    include_once '../objects/test.php';
    include_once '../objects/problem.php';
    include_once '../objects/result.php';
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
        $test = new Test($db);
        $problem = new Problem($db);
        $utils = new Utils();
    
        // set tcode and pcode to upper case
        $_POST['tcode'] = strtoupper($_POST['tcode']);
        $_POST['pcode'] = strtoupper($_POST['pcode']);
        $test->tcode = $_POST['tcode'];
        $problem->pcode = $_POST['pcode'];

        if($utils->tableExists($test->tcode))
        {
            echo "<div class='alert alert-danger'>";
                echo "<span class='glyphicon glyphicon-alert'></span> WARNING: You are NOT ALLOWED to use this test code.";
            echo "</div>";
        }
    
        // check if tcode already exists
        else if($test->tcodePresent())
        {
            echo "<div class='alert alert-danger'>";
                echo "This test code is already used for another test.";
            echo "</div>";
        }

        else if(!$problem->pcodePresent())
        {
            echo "<div class='alert alert-danger'>";
                echo "The problem code you mentioned does not exist. <br>Open the problems tab to see the available problems or create a new problem.";
            echo "</div>";
        }
    
        else
        {
            $test->tname=$_POST['tname'];
            $test->pcode=$_POST['pcode'];
            $test->status=0;
            $test->utils = $utils;
            $result = new Result($db, $_POST['tcode']);

            if($test->create())
            {
                try
                {
                    $result->createTable();
                    echo "<div class='alert alert-info'>";
                        echo "<h3><b>{$test->tname} ({$test->tcode}) Created Successfully.</b></h3><br>";
                        echo "You can start and stop the test whenever required from the <a href='read_tests.php'><b>Tests Tab</b></a>.<br>";
                        echo "Click on the status value of test to toggle the status from 'Live' to 'Closed' or 'Closed' to 'Live'";
                    echo "</div>";

                    //Test Details Link
                    echo "<div class='alert alert-info'>";
                        echo "<b><a href='{$home_url}admin/test/details.php?tcode={$test->tcode}'><span class='glyphicon glyphicon-link'></span> Test ({$test->tname}) Details </a></b>";
                    echo "</div>";

                    //home page link
                    echo "<div class='alert alert-info'>";
                        echo "<b><a href='index.php'> <span class='glyphicon glyphicon-home'></span> Go to Home Page </a></b>";
                    echo "</div>";

                    $created = true;

                    //empty posted values
                    $_POST=array();
                }
                catch(Exception $e)
                {
                    $test->delete();

                    echo "<div class='alert alert-danger'>";
                        echo "Some error occurred. Change the testcode and try again.";
                    echo "</div>";
                }
            }
            else
            {
                echo "<div class='alert alert-danger' role='alert'>Unable to Create Test. Please try again.</div>";
            }
        }
    }

    if($created == false)
    {
        include "test_form.php";
    }

    echo "</div>";
    
    // include page footer HTML
    include_once "layout_foot.php";
?>