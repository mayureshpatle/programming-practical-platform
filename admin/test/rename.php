<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_POST['tcode']))
{
    try
    {
        $test = new Test($db);
        $test->tcode = $_POST['tcode'];
        if($test->tcodeExistsAdmin())
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
$page_title = "Test: {$test->tcode}";

if(isset($_POST['tname']))
{
    $test->tname = $_POST['tname'];
    $action = $test->setName() ? "renamed" : "rename_failed";
    $_POST = array();
    header("Location: details.php?tcode={$test->tcode}&action={$action}");
}

// include page header HTML
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";
?>

<form action='rename.php' method='post' class='col-md-12'>
    <input name='tcode' type='hidden' value='<?php echo $test->tcode; ?>' />;
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Test Name</b></td>
            <td><input type='text' name='tname' class='form-control' required value='<?php echo $test->tname; ?>' /></td>
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
        <p><a href='details.php?tcode=<?php echo $test->tcode; ?>&action=cancelled' class='btn btn-danger btn-block' onclick="return confirm('Do you really want to abort renaming this test?')">
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>

<?php
include_once "../layout_foot.php";
?>