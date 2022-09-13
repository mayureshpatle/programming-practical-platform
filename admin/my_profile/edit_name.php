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
        $go_ahead = true;
    }
}
catch(Exception $e)
{
    //echo "damnnn...";
}

if(!$go_ahead) header("Location: {$home_url}");

// set page title
$page_title = "My Profile: Edit Name";


if(isset($_POST['name']))
{
    $_POST['name'] = strtoupper($_POST['name']);
    $user->name = $_POST['name'];
    $action = $user->updateName() ? "renamed" : "rename_failed";
    $_POST = array();
    header("Location: details.php?id={$user->id}&action={$action}");
}

// include page header HTML
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";
?>

<form action='edit_name.php' method='post' class='col-md-12'>
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Name</b></td>
            <td><input type='text' name='name' class='form-control' required value='<?php echo $user->name; ?>' /></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="col-md-2">
        <p><button type='submit' class='btn btn-primary btn-block'>
            <b><span class='glyphicon glyphicon-floppy-disk'></span> Save</b>
        </button></p>
    </div>
        
    <div class="col-md-2">
        <p><a href='details.php?action=cancelled' class='btn btn-danger btn-block'>
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>

<?php
include_once "../layout_foot.php";
?>