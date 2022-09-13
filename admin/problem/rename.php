<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/problem.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_POST['pcode']))
{
    try
    {
        $problem = new Problem($db);
        $problem->pcode = $_POST['pcode'];
        if($problem->pcodeExists())
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
$page_title = "Problem: {$problem->pcode}";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

if(isset($_POST['pname']))
{
    $problem->pname = $_POST['pname'];
    $action = $problem->update() ? "renamed" : "rename_failed";
    $_POST = array();
    header("Location: details.php?pcode={$problem->pcode}&action={$action}");
}

// include page header HTML
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";
?>

<form action='rename.php' method='post' class='col-md-12'>  <?php echo $pcode_ip;?>
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Problem Name</b></td>
            <td><input type='text' name='pname' class='form-control' required value='<?php echo $problem->pname; ?>' /></td>
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
        <p><a href='details.php?pcode=<?php echo $problem->pcode; ?>&action=cancelled' class='btn btn-danger btn-block' onclick="return confirm('Do you really want to abort renaming this problem?')">
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>

<?php
include_once "../layout_foot.php";
?>