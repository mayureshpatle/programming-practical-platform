<?php
    // core configuration
    include_once "config/core.php";
    include_once "libs/php/utils.php";
    
    // set page title
    $page_title = "Register For Test";
    
    // include login checker
    $require_login=true;
    include_once "login_checker.php";
    
    // include classes
    include_once 'config/database.php';
    include_once 'objects/test.php';
    include_once 'objects/access.php';
    include_once 'objects/result.php';

    // include page header HTML
    include_once 'layout_head.php';

    echo "<div class='col-md-12'>";

    $registered = false;    
    
    // if form was posted
    if($_POST)
    {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
    
        // initialize objects
        $test = new Test($db);

        $_POST['tcode'] = strtoupper($_POST['tcode']);
        $test->tcode=$_POST['tcode'];
    
        // check if tcode exists
        if(!$test->tcodePresent() || !$test->getRegKey())
        {
            echo "<div class='alert alert-danger'>";
                echo "Invalid Test Code.";
            echo "</div>";
        }

        else 
        {
            $access = new Access($db);
            $access->tcode = $_POST['tcode'];
            $access->user_id = $_SESSION['id'];

            if($test->reg_key != $_POST['reg_key'])
            {
                echo "<div class='alert alert-danger'>";
                    echo "Invalid Registration Key.";
                echo "</div>";
            }
        
            else
            {
                $access->roll_no = $_POST['roll_no'];
                $access->utils = new Utils();
                $result  = new Result($db, $access->tcode);
                $result->name = $_SESSION['name'];
                $result->user_id = $_SESSION['id'];
                $result->roll_no = $access->roll_no;
                $result->score = 0;

                try
                {
                    $go_ahead = true;

                    if($access->accessPresent())
                    {
                        if(!$result->recordPresent())
                        {
                            $access->delete();
                        }
                        else
                        {
                            echo "<div class='alert alert-danger' role='alert'>You are already registered for this test ({$_POST['tcode']}).</div>";
                            $go_ahead = false;
                        }
                    }
                    else if($result->recordPresent())
                    {
                        $result->deleteRecord();
                    }

                    if($go_ahead)
                    {
                        if($access->create())
                        {
                            if($result->create())
                            {
                                echo "<div class='alert alert-info'> <h3>Successfully Registered for Test {$_POST['tcode']}</h3>
                                    <br /> Open <a href='{$home_url}my_tests/'>My Tests Tab</a> to view all your registered tests.</div>";

                                echo "<div class='alert alert-info'>
                                        <a href='{$home_url}'> <span class='glyphicon glyphicon-home'></span> Go to Home Page </a>
                                    </div>";
                                
                                $registered = true;

                                $_POST = array();
                            }
                            else
                            {
                                $access->delete();
                            }
                        }

                        if(!$registered)
                        {
                            echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
                        }
                    }
                }
                catch(Exception $e)
                {
                    echo "<div class='alert alert-danger' role='alert'>{$e->getMessage()} <br \>Some error occurred Please try again.</div>";
                }
            }
        }
    }

    if($registered == false)
    {
        include "test_registration_form.php";
    }

    echo "</div>";
    
    // include page footer HTML
    include_once "layout_foot.php";
?>