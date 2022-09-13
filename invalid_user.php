<?php
include_once "config/core.php";

$page_title = "Login";
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
    
    if(!$user->verifyAccessCode()){
        echo "<div class='alert alert-danger'>Invalid Details OR the account is already verified.</div>";
    }
    
    // redirect to login
    else{
        // redirect
        header("Location: {$home_url}login.php?action=user_verified");
    }
}
else
{
    echo "<div class='alert alert-danger'>
            <h4>You cannot login without validating you account. Check mail from A14Project (or {$mailer_mail}) for verification link.</h4>
        </div>

        <table class='table'><tr><td></td></tr></table>

        <div class='alert alert-success'>
            <h4><b>NOTE:</b> You can also fill the following details to validate your account.</h4>
        </div>";
}

include_once "libs/js/utils.php"; 

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>User ID</td>
            <td><input type='text' name='id' class='form-control' placeholder="College Registration Number, Ex: 17010471" required value="<?php echo isset($_POST['id']) ? htmlspecialchars($_POST['id'], ENT_QUOTES) : '';  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>Access Code</td>
            <td><input type='text' name='access_code' class='form-control' placeholder="Access code" required value="<?php echo isset($_POST['access_code']) ? htmlspecialchars($_POST['access_code'], ENT_QUOTES) : '';  ?>" minLength=32 maxLength=32 onkeypress="return keyInput(event)"></td>
        </tr>
 
        <tr>
            <td>
                <button type="submit" class="btn btn-lg btn-primary">
                    <b><span class="glyphicon glyphicon-check"></span> Validate</b>
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