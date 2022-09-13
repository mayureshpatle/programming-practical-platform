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

if(isset($_GET['id']))
{
    try
    {
        $user = new User($db);
        $user->id = $_GET['id'];
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

// set page title
$page_title = "User: {$user->id}";

// include page header HTML
include_once "../layout_head.php";

$type_btn = $user->type ? "'btn btn-success'" : "'btn btn-warning'";
$type = "<span class='glyphicon glyphicon-user'></span> " . ($user->type ? "TEACHER" : "STUDENT");
$new_type = $user->type ? "STUDENT" : "TEACHER";
$type_warn = "You are about to change the type of user {$user->id} ($user->name) to $new_type. This will make serious changes in their access privileges."

;$verified = "<big><b><span class='glyphicon glyphicon-" . ( $user->status ? "ok text-success'>" : "remove text-danger'>" ) . "</span></b></big>"; 
$status = $user->status ? "" : ( "<big>" . ( $user->mail_status ? "<span class='label label-success'>Verification Mail Sent</span>" : "<span class='label label-danger'>Verification Mail Not Sent</span>" ) . "</big>" );

if(isset($_GET['action']))
{
    echo "<div class='col-md-12'>";

    $action = $_GET['action'];
    if($action == "regenerated")
    {
        echo "<div class='alert alert-success'>
                Access Code was successfully changed for this user. You may need to inform the user about this.
            </div>";
    }
    else if($action == "regeneration_failed")
    {
        echo "<div class='alert alert-danger'>Unable to regenerate access code. Try again!</div>";
    }
    else if($action == "cancelled")
    {
        echo "<div class='alert alert-warning'>Action Aborted! No changed were made.</div>";
    }
    else if($action == "renamed")
    {
        echo "<div class='alert alert-success'>User's name has been updated successfully.</div>";
    }
    else if($action == "rename_failed")
    {
        echo "<div class='alert alert-danger'>Some error occurred while changing user's name. Please try again.</div>";
    }
    else if($action == "email_changed")
    {
        echo "<div class='alert alert-success'>User's Email ID has been updated successfully.</div>";
    }
    else if($action == "toggled")
    {
        echo "<div class='alert alert-success'>User's type has been updated successfully.</div>";
    }
    else if($action == "toggle_failed")
    {
        echo "<div class='alert alert-danger'>Some error occurred while changing user's type. Please try again.</div>";
    }

    echo "</div>";
}

$reset_confirm = "WARNING: Current Access Code will be invalidated. This action cannot be undone.";

$name_warn = "WARNING: Are you sure you want to edit the name of this user?";

$email_warn = "WARNING: This user\'s account will be marked unverified if you change his/her email ID. If you think his/her account was accessed by some other person then it is suggested that you should regenerate the Access Code of this user as soon as you change his/her email ID.";
$change_text = "<span class='glyphicon glyphicon-warning-sign'></span> <b>Change</b>";

$delete_warn = "Are you sure you want to delete this user\'s account? This will delete all his/her records in all the tests including his/her submitted codes. THIS ACTION CANNOT BE UNDONE.";

?>

<div class='col-md-12'>
    <table class='table table-hover'>

        <form action="change_type.php" method="post">
        <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
        <input type="hidden" name="type" value=<?php echo 1-(int)$user->type; ?> />
        <tr>
            <td class='col-md-3'><b>User Type</b></td>
            <td class='col-md-7'><span class=<?php echo $type_btn; ?> disabled="disabled"><b><?php echo $type; ?></span></b></td>
            <td><button type="submit" class="btn btn-primary btn-block" onclick="return confirm('<?php echo $type_warn; ?>')"><b>Change</b></button></td>
        </tr>
        </form>

        <tr>
            <td class='col-md-3'><b>User ID</b></td>
            <td class='col-md-7'><?php echo $user->id; ?></td>
            <td></td>
        </tr>

        <tr>
            <td class='col-md-3'><b>Verified</b></td>
            <td class='col-md-7'><?php echo $verified . " " . $status; ?></td>
            <td></td>
        </tr>

        <form method="post" action="change_email.php">
        <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
        <tr>
            <td class='col-md-3'><b>Email ID</b></td>
            <td class='col-md-7'><?php echo $user->email; ?></td>
            <td><button type="submit" class="btn btn-danger btn-block active" onclick="return confirm('<?php echo $email_warn; ?>')" ><?php echo $change_text ?></button></td>
        </tr>
        </form>

        <form method="post" action="edit_name.php">
        <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
        <tr>
            <td class='col-md-3'><b>Name</b></td>
            <td class='col-md-7'><?php echo $user->name; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $name_warn; ?>')"><b>Edit</b></button></td>
        </tr>
        </form>

        <form method="post" action="regenerate_key.php">
        <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
        <tr>
            <td class='col-md-3'><b>Access Code</b></td>
            <td class='col-md-7'><?php echo $user->access_code; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $reset_confirm; ?>')"><b>Regenerate</b></button></td>
        </tr>
        </form>
    </table>

<div class="row">
<p class="col-md-3">
<a href="tests.php?id=<?php echo $user->id; ?>" target="_blank" class="btn btn-primary btn-lg btn-block"><b><span class="glyphicon glyphicon-education"></span> Registered Tests</b></a>
</p>

<form action="delete.php" method="post">
<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
<p class="col-md-3">
<button type="submit" action="delete.php" class="btn btn-danger btn-lg btn-block" onclick="return confirm('<?php echo $delete_warn; ?>')"><b><span class="glyphicon glyphicon-trash"></span> Delete User</b></button>
</p>
</form>
</div>

</div>

<br /><br />

<?php
include_once "../layout_foot.php";
?>