<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
include_once '../../objects/result.php';
include_once '../../objects/problem.php';
include_once '../../libs/js/utils.php';

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

$pcode_valid = true;

$same_pcode = false;

if(isset($_POST['pcode']))
{
    if($test->pcode == $_POST['pcode'])
    {
        $same_pcode = true;
    }
    else
    {   
        $problem = new Problem($db);
        $problem->pcode = $_POST['pcode'];
        $pcode_valid = $problem->pcodePresent();

        if($pcode_valid)
        {
            $result = new Result($db, $test->tcode);
            $test->pcode = $_POST['pcode'];
            $status = $test->changeProblem();
            $status = $result->reset() && $status;
            $action = $status ? "changed" : "change_failed";
            $_POST = array();
            header("Location: details.php?tcode={$test->tcode}&action={$action}");
        }
    }
}

// include page header HTML
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";

if($same_pcode)
{
    echo "<div class='col-md-12'>
            <div class='alert alert-danger'>
                You didn't change the problem code. Press the Cancel button if you don't want to change the Problem.<br />
            </div>
        </div>";
}

if(!$pcode_valid)
{
    echo "<div class='col-md-12'>
            <div class='alert alert-danger'>
                The Problem Code that you mentioned does not exist.<br />
            </div>
        </div>";
}

$change_warn = "WARNING: If you confirm this action then all previous submissions will be invalidated. THIS ACTION CANNOT BE UNDONE.";
$change_text = "<span class='glyphicon glyphicon-warning-sign'></span> Change";

?>

<form action='change_problem.php' method='post' class='col-md-12' onkeypress="return keyInput(event)">
    <input name='tcode' type='hidden' value='<?php echo $test->tcode; ?>' />;
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Problem Code</b></td>
            <td><input type='text' name='pcode' class='form-control' required value='<?php echo isset($_POST['pcode']) ? $_POST['pcode'] : $test->pcode; ?>' /></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="col-md-2">
        <p><button type='submit' class='btn btn-warning btn-block' onclick="return confirm('<?php echo $change_warn; ?>');">
            <b><?php echo $change_text; ?></b>
        </button></p>
    </div>
        
    <div class="col-md-2">
        <p><a href='details.php?tcode=<?php echo $test->tcode; ?>&action=cancelled' class='btn btn-danger btn-block' onclick="return confirm('Do you really want to abort changing the problem?')">
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>

<?php
include_once "../layout_foot.php";
?>