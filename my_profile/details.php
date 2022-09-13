<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
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

// set page title
$page_title = "My Profile";

// include page header HTML
include "../layout_head.php";

if(isset($_GET['action']))
{
    echo "<div class='col-md-12'>";

    $action = $_GET['action'];
    if($action == "regenerated")
    {
        echo "<div class='alert alert-success'>
                Your Access Code was changed successfully. Copy and save your new access code for later use (in case you forget password or change email ID)
            </div>";
    }
    else if($action == "regeneration_failed")
    {
        echo "<div class='alert alert-danger'>Unable to regenerate your access code. Try again!</div>";
    }
    else if($action == "cancelled")
    {
        echo "<div class='alert alert-warning'>Action Aborted! No changed were made.</div>";
    }
    else if($action == "renamed")
    {
        echo "<div class='alert alert-success'>Your name has been updated successfully.</div>";
    }
    else if($action == "rename_failed")
    {
        echo "<div class='alert alert-success'>Some error occurred while changing your name. Please try again.</div>";
    }

    echo "</div>";
}

$reset_confirm = "WARNING: Current Access Code will be invalidated. This action cannot be undone.";

$name_warn = "WARNING: Are you sure you want to edit your name?";

$email_warn = "WARNING: Your account will be marked unverified after this, and you will be logged out. It is suggested that you copy and save your access code before proceeding ahead. You will need it in case of any failure in sending verifcation mail.";
$change_text = "<span class='glyphicon glyphicon-warning-sign'></span> <b>Change</b>";

?>

<div class='col-md-12'>
    <table class='table table-hover'>

        <tr>
            <td class='col-md-3'><b>User Type</b></td>
            <td class='col-md-7'><span class='btn btn-info active' disabled="disabled"><b><span class="glyphicon glyphicon-user"></span> STUDENT</span></b></td>
            <td></td>
        </tr>

        <tr>
            <td class='col-md-3'><b>User ID</b></td>
            <td class='col-md-7'><?php echo $user->id; ?></td>
            <td></td>
        </tr>

        <form method="post" action="change_email.php">
        <tr>
            <td class='col-md-3'><b>Email ID</b></td>
            <td class='col-md-7'><?php echo $user->email; ?></td>
            <td><button type="submit" class="btn btn-danger btn-block active" onclick="return confirm('<?php echo $email_warn; ?>')" ><?php echo $change_text ?></button></td>
        </tr>
        </form>

        <form method="post" action="edit_name.php">
        <tr>
            <td class='col-md-3'><b>Name</b></td>
            <td class='col-md-7'><?php echo $user->name; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $name_warn; ?>')"><b>Edit</b></button></td>
        </tr>
        </form>

        <form method="post" action="regenerate_key.php">
        <tr>
            <td class='col-md-3'><b>Access Code</b></td>
            <td class='col-md-7'><?php echo $user->access_code; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $reset_confirm; ?>')"><b>Regenerate</b></button></td>
        </tr>
        </form>
    </table>

</div>

<form class="col-md-3" method="post" action="change_password.php">
<button type="submit" class="btn btn-primary btn-lg btn-block"><b><span class="glyphicon glyphicon-refresh"></span> Change Password</b></button>
</form>
<br /><br />

<?php
include_once "../layout_foot.php";
?>