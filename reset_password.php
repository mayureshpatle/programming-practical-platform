<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Reset Password";
 
// include login checker
include_once "login_checker.php";
 
// include classes
include_once "config/database.php";
include_once "objects/user.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
 
// include page header HTML
include_once "layout_head.php";
 
echo "<div class='col-sm-12'>";
 
// get given access code & user id
$access_code=isset($_GET['access_code']) ? $_GET['access_code'] : die("ERROR: Invalid Link.");
$user_id=isset($_GET['id']) ? $_GET['id'] : die("ERROR: Invalid Link.");

// check if user ID & access code exist
$user->access_code=$access_code;
$user->id=$user_id;

if(!$user->validateAccessCode()){
    die("ERROR: Invalid / Expired Link. Use the link in latest mail from {$mailer_mail}.");
}
    
else
{
    // if form was posted
    if($_POST){

        if($_POST['password']!=$_POST['confirm_password'])
        {
            echo "<div class='alert alert-danger'>";
                echo "Password and Confirm Password don't match. Kindly enter same password in both the fields.";
            echo "</div>";
        }

        else
        {
            // set values to object properties
            $user->password=$_POST['password'];
        
            // reset password
            if($user->updatePassword()){
                header("Location: {$home_url}login/?action=password_reset");
            }
        
            else{
                echo "<div class='alert alert-danger'>Unable to reset password.</div>";
            }
        }
    }

    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?id={$user_id}&access_code={$access_code}' method='post'>
            <table class='table table-hover table-responsive'>
                <tr>
                    <td class='col-sm-3'>Password</td>
                    <td><input type='password' name='password' class='form-control' minLength=8 maxLength=13 required></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control' minLength=8 maxLength=13 required></td>
                </tr>
                <tr>
                    <td><button type='submit' class='btn btn-primary'><b>Reset Password</b></button></td>
                    <td><div class='alert alert-info'>NOTE: Password must be 8 to 13 characters long.</div></td>
                </tr>
            </table>
        </form>";
}
 
echo "</div>";
 
// include page footer HTML
include_once "layout_foot.php";
?>