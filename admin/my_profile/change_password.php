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

try
{
    $user = new User($db);
    $user->id = $_SESSION['id'];
    if($user->idExists())
    {
        if($user->status) $go_ahead = true;
    }
}
catch(Exception $e)
{
    //echo "damnnn...";
}

if(!$go_ahead) header("Location: ../../logout.php?action=logout_confirmed");

$page_title = "My Profile: Change Password";
include_once "../layout_head.php";

echo "<div class='col-md-12'>";

$done = false;

if($_POST)
{   
    if(!password_verify($_POST['old_password'],$user->password)){
        session_destroy();
        echo "<div class='alert alert-danger'>WARNING: Entered Password was WRONG. You're logged out.</div>";
        $done = true;
    }
    
    else{
        if($_POST['new_password']!=$_POST['confirm_password'])
        {
            echo "<div class='alert alert-danger'>";
                echo "New Password and Confirm New Password don't match. Kindly enter same password in both the fields.";
            echo "</div>";
        }
        else
        {
            // set values to object properties
            $user->password=$_POST['new_password'];
        
            // reset password
            if($user->updatePassword())
            {
                echo "<div class='alert alert-success'>Your password has been changed successfully.</div>";
                $done = true;
            }
        
            else
            {
                echo "<div class='alert alert-danger'>Unable to reset your password.</div>";
            }
        }
    }
}

if(!$done) include_once "password_form.php";
 
include_once "../layout_foot.php";
?>