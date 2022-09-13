<?php
include_once "config/core.php";

$page_title = "Reset Password";
$require_login=false;
include_once "login_checker.php";

include_once "layout_head.php";

echo "<div class='col-md-12'>";

if($_POST)
{
    // include classes
    include_once 'config/database.php';
    include_once 'objects/user.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize objects
    $user = new User($db);
    
    // set access code
    $user->access_code = $_POST['access_code'];
    $user->id = $_POST['id'];
    
    if(!$user->validateAccessCode()){
        echo "<div class='alert alert-danger'>Invalid User ID or Access Code.</div>";
    }
    
    else{
        if($_POST['password']!=$_POST['confirm_password'])
        {
            echo "<div class='alert alert-danger'>";
                echo "New Password and Confirm New Password don't match. Kindly enter same password in both the fields.";
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
}

include_once "libs/js/utils.php"; 

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>User ID</td>
            <td><input type='text' name='id' class='form-control' placeholder="College Registration Number" required value="<?php echo isset($_POST['id']) ? htmlspecialchars($_POST['id'], ENT_QUOTES) : '';  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>New Password</td>
            <td><input type='password' name='password' class='form-control' placeholder="Password" required id='passwordInput' minLength=8 maxLength=13></td>
        </tr>

        <tr>
            <td>Confirm New Password</td>
            <td><input type='password' name='confirm_password' class='form-control' placeholder="Confirm Password" required id='confirmPasswordInput' minLength=8 maxLength=13></td>
        </tr>

        <tr>
            <td>Access Code</td>
            <td><input type='text' name='access_code' class='form-control' placeholder="Access code" required value="<?php echo isset($_POST['access_code']) ? htmlspecialchars($_POST['access_code'], ENT_QUOTES) : '';  ?>" minLength=32 maxLength=32 onkeypress="return keyInput(event)"></td>
        </tr>
 
        <tr>
            <td>
                <button type="submit" class="btn btn-lg btn-primary">
                    <b><span class="glyphicon glyphicon-refresh"></span> Reset Password</b>
                </button>
            </td>
            <td>
                <div class="alert alert-info">
                    NOTE: Ask any teacher for your access code.
                </div>
            </td>
        </tr>
 
    </table>
</form>

</div>

<?php 
include_once "layout_foot.php";
?>