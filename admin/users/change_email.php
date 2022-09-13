<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/user.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_POST['id']))
{
    try
    {
        $user = new User($db);
        $user->id = $_POST['id'];
        if($user->idExists())
        {
            $go_ahead = true;
        }
    }
    catch(Exception $e)
    {
        //echo "damnnn...";
    }
}

if(!$go_ahead) header("Location: {$home_url}");

$same = false;
$error = false;
$include_form = true;
$email = $user->email;

if(isset($_POST['email']))
{
    $_POST['email'] = trim($_POST['email']);
    $email = $_POST['email'];
    if($_POST['email'] == $user->email)
    {
        $same = true;
    }
    else
    {
        $user->email = $_POST['email'];
        if($user->updateEmail())
        {
            $_POST = array();
            //mailer class
            $mail_autoload = '../../libs/phpmailer/vendor/autoload.php';
            include_once '../../objects/mailer.php';
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
    $page_title = "Change Email ID (User: {$user->id})";
}
else 
{
    // set page title
    $page_title = "Email ID Changed (User: {$user->id})";
}
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";

echo "<div class='col-md-12'>";

if($same)
{
    echo "<div class='alert alert-danger'>
            You didn't edit the Email ID. Press the Cancel button if you don't want to change Email ID of this user.
        </div>";
}

else if($error)
{
    echo "<div class='alert alert-danger'>Some error occurred while updating the Email ID of this user. Please try again.</div>";
}

if($include_form) 
{
    include_once "email_form.php";
}
else 
{
    echo "<div class='alert alert-success'>
            Email ID of this user was changed successfully. His/her account is now marked as unverified.
        </div>";

    if($mail_status=="Successful")
    {
        echo "<div class='alert alert-success'>
                Verification link has been mailed to {$user->email}. Ask them to check mail from {$mailer_mail}.
            </div>";
    }
    else
    {
        echo "<div class='alert alert-danger'>
                Some error occurred while sending the verification link to {$user->email}. <br />
                Kindly share their access code with them for verifying their account.
            </div>";
    }

    echo "<a href='details.php?id={$user->id}&action=email_changed' class='btn btn-lg btn-danger'><b><span class='glyphicon glyphicon-log-out'></span> Exit</b></a>";
}

include_once "../layout_foot.php";
?>