<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/user.php';
include_once '../../objects/access.php';
include_once '../../libs/php/utils.php';

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

$wrong_pass = false;
$include = true;
$error = false;
$deleted = false;

if(isset($_POST['password']))
{
    $teacher = new User($db);
    $teacher->id = $_SESSION['id'];
    if($teacher->idExists())
    {
        if(!$teacher->status) header("Location: ../../logout.php?action=logout_confirmed");
        if(!password_verify($_POST['password'],$teacher->password)) $wrong_pass = true;
        else
        {
            if($user->delete(new Utils(), new Access($db), $db))
            {
                $deleted = true;
            }
            else
            {
                $error = true;
            }
            $include = false;
        }
    }
    else
    {
        header("Location: ../../logout.php?action=logout_confirmed");
    }
}

$page_title = "Delete User ($user->id)";
include_once "../layout_head.php";

echo "<div class='col-md-12'>";

if($wrong_pass)
{
    echo "<div class='alert alert-danger'>WARNING: You've entered WRONG PASSWORD.</div>";
}
else if($error)
{
    echo "<div class='alert alert-danger'>Some error occurred while deleting this user.</div>";
}
else if($deleted)
{
    echo "<div class='alert alert-success'>User deleted successfully.</div>";
}

if($include) include_once "delete_form.php";

include_once "../layout_foot.php";
?>