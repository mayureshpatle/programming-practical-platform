<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Forgot Password";
 
// include login checker
include "login_checker.php";
 
// include classes
include_once "config/database.php";
include_once 'objects/user.php';
include_once "libs/php/utils.php";

//mailer class
$mail_autoload = 'libs/phpmailer/vendor/autoload.php';
include_once 'objects/mailer.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
$utils = new Utils();
 
// include page header HTML
include_once "layout_head.php";
 
// if the login form was submitted
if($_POST){
 
    echo "<div class='col-sm-12'>";
 
        // check if username and password are in the database
        $user->id=$_POST['id'];
 
        if($user->idExists()){
 
            // update access code for user
            $access_code=$utils->getToken();
 
            $user->access_code=$access_code;
            if($user->updateAccessCode()){
 
                // send reset link
                include_once "forgot_pwd_mail.php";

                if($mail_status=="Successful")
                {
                    echo "<div class='alert alert-success'>Further details are mailed to {$user->email}. Check inbox for further steps.</div>";
                }
 
                // message if unable to send email for password reset link
                else
                {
                    echo "<div class='alert alert-danger'>ERROR: Unable to send reset link. <br />Error Details: {$mail_status}.<br />Contact any teacher for further steps.</div>"; 
                }
            }
 
            // message if unable to update access code
            else
            {
                echo "<div class='alert alert-danger'>ERROR: Unable to update access code.</div>"; 
            }
 
        }
 
        // message if user_id does not exist
        else
        {
            echo "<div class='alert alert-danger'>Your User ID cannot be found.</div>"; 
        }
 
    echo "</div>";
}
 
// show forgot password HTML form
echo "<div class='col-md-4'></div>";
echo "<div class='col-md-4'>";
 
    echo "<div class='account-wall'>
        <div id='my-tab-content' class='tab-content'>
            <div class='tab-pane active' id='login'>
                <img class='profile-img' src='{$home_url}images/logo.png'>
                <form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                    <input type='text' name='id' class='form-control' placeholder='Your User ID' required autofocus style='margin-top:1em;'>
                    <input type='submit' class='btn btn-lg btn-primary btn-block' value='Send Reset Link' style='margin-top:1em;' />
                </form>
            </div>
        </div>
    </div>";
 
echo "</div>";
echo "<div class='col-md-4'></div>";
 
// footer HTML and JavaScript codes
include_once "layout_foot.php";
?>