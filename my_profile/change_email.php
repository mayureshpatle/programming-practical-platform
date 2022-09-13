<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in
$require_login = true;
include_once "../login_checker.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/user.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

try
{
    $user = new User($db);
    $user->id = $_SESSION['id'];
    if($user->idExists())
    {
        $go_ahead = true;
    }
}
catch(Exception $e)
{
    //echo "damnnn...";
}

if(!$go_ahead) header("Location: {$home_url}");

$same = false;
$error = false;
$include_form = true;
$wrong_password = false;

$email = $user->email;

if(isset($_POST['email']))
{
    $_POST['email'] = trim($_POST['email']);
    $email = $_POST['email'];
    if($_POST['email'] == $user->email)
    {
        $same = true;
    }
    else if(!password_verify($_POST['password'], $user->password))
    {
        $wrong_password = true;
    }
    else
    {
        $user->email = $_POST['email'];
        if($user->updateEmail())
        {
            $_POST = array();
            session_destroy();

            //mailer class
            $mail_autoload = '../libs/phpmailer/vendor/autoload.php';
            include_once '../objects/mailer.php';
            include_once "verification_mail.php";
            $include_form = false;
        }
        else
        {
            $error = true;
        }
    }
}

// include page header HTML
if($include_form) 
{
    // set page title
    $page_title = "My Profile: Change Email ID";
}
else 
{
    // set page title
    $page_title = "Email ID Changed";
}
include_once "../layout_head.php";
include_once "../libs/js/utils.php";

echo "<div class='col-md-12'>";

if($same)
{
    echo "<div class='alert alert-danger'>
            You didn't edit the Email ID. Press the Cancel button if you don't want to change your Email ID.
        </div>";
}

else if($error)
{
    echo "<div class='alert alert-danger'>Some error occurred while updating your Email ID. Please try again.</div>";
}

else if($wrong_password)
{
    echo "<div class='alert alert-danger'></b>ERROR: WRONG PASSWORD</b></div>";
}

if($include_form) 
{
    include_once "email_form.php";
}
else 
{
    echo "<div class='alert alert-success'>
            Your Email ID was changed successfully. Your account is now marked as unverified.
            <h4>You have been logged out from the previous session.</h4>
        </div>";

    if($mail_status=="Successful")
    {
        echo "<div class='alert alert-success'>
                Verification link has been mailed to {$user->email}. Check mail from {$mailer_mail}.
            </div>";
    }
    else
    {
        echo "<div class='alert alert-danger'>
                Some error occurred while sending the verification link to {$user->email}. <br />
                Hope you read the warning and have copied your access code. If not then ask any teacher for your access code.
            </div>";
    }

    unset($user);
}

include_once "../layout_foot.php";
?>