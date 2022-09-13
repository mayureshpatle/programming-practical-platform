<?php
    // core configuration
    include_once "config/core.php";
    
    // set page title
    $page_title = "User Registration";
    
    /// include login checker
    $require_login=false;
    include "login_checker.php";
    
    // include classes
    include_once 'config/database.php';
    include_once 'objects/user.php';
    include_once 'libs/php/utils.php';

    //mailer class
    $mail_autoload = 'libs/phpmailer/vendor/autoload.php';
    include_once 'objects/mailer.php';

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
        $user = new User($db);
        $utils = new Utils();
    
        // set user ID to detect if it already exists
        $user->id=$_POST['user_id'];
        $_POST['name'] = strtoupper($_POST['name']);
    
        // check if User ID already exists
        if($user->idPresent())
        {
            echo "<div class='alert alert-danger'>";
                echo "The Registration Number you specified is already registered.";
            echo "</div>";
        }

        else if($_POST['password']!=$_POST['confirm_password'])
        {
            echo "<div class='alert alert-danger'>";
                echo "Password and Confirm Password don't match. Kindly enter same password in both the fields.";
            echo "</div>";
        }
    
        else
        {
            $user->id=$_POST['user_id'];
            $user->name=$_POST['name'];
            $user->email=$user->id . "@ycce.in";
            $user->type=0;
            $user->password=$_POST['password'];
            $user->status=0;
            $access_code=$utils->getToken();
            $user->access_code=$access_code;

            if($user->create())
            {
                include "registration_mail.php";

                if($mail_status=="Successful")
                {   
                    echo "<div class='alert alert-info'>";
                    echo "<h3><b>User Created Successfully.</b></h3><br>";
                    echo "Further details are mailed to you ( <b>{$_POST['name']}</b> ) on <b>{$user->email}</b>.<br>";
                    echo "Please check mail from <b>{$mailer_mail}</b> and complete the mentioned steps.";
                    echo "</div>";

                    //go to home page
                    echo "<div class='alert alert-info'>";
                    echo "<b><a href='index.php'> Go to Home Page </a></b>";
                    echo "</div>";
                }
                else
                {
                    //mail failure
                    echo "<div class='alert alert-danger'>";
                    echo "User creation was successful but <b>unable to send verification email.</b><br />";
                    echo "Contact any registered teacher for further actions.<br />";
                    echo "<b>Error Details:</b> {$mail_status}<br /><br />";
                    echo "</div>";
                    $user->mail_failed();
                }
                
                $registered = true;

                //empty posted values
                $_POST=array();
            }
            else
            {
                echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
            }
        }
    }

    if($registered == false)
    {
        include "registration_form.php";
    }

    echo "</div>";
    
    // include page footer HTML
    include_once "layout_foot.php";
?>