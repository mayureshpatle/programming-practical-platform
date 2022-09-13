<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
include_once '../../objects/problem.php';
include_once '../../objects/result.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_GET['tcode']))
{
    try
    {
        $test = new Test($db);
        $test->tcode = $_GET['tcode'];
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

// include page header HTML
include_once "../layout_head.php";


include "action_response.php";

$tcode_ip = "<input name='tcode' type='hidden' value='{$test->tcode}' />";

$reset_confirm = "WARNING: Current Registration Key will be invalidated and no student will be able to register for test using the current registration key after this. This action cannot be undone.";

$problem = new Problem($db);
$problem->pcode = $test->pcode;
$problem->pcodeExists();

$pcode = $problem->pcode;
$change_warn = "WARNING: All previous submissions will be invalidated. THIS ACTION CANNOT BE UNDONE.";
$change_text = "<span class='glyphicon glyphicon-warning-sign'></span> Change";

$pname = $problem->pname;
$pstatus = $problem->ready;
$detail_link = "../problem/details.php?pcode={$test->pcode}";
$detail_text = "<b><span class='glyphicon glyphicon-new-window'></span> Details</b>";
$ready = $pstatus ? " <big><span class='label label-success'>Ready</span></big>" : " <big><span class='label label-danger'>Not Ready</span></big>";
$detail_note = "NOTE: Refresh this page if you make any changes in the problem to see if the changes were synced with this test.";

$status = $test->status ? "<b class='text-success'><span class='glyphicon glyphicon-time'></span> Live</b>" : "<b class='text-danger'><span class='glyphicon glyphicon-off'></span> Closed</b>";
$new_state = $test->status ? "<span class='glyphicon glyphicon-off'></span> Stop" : "<span class='glyphicon glyphicon-play-circle'></span> Start";
$btn = "btn btn-block btn-lg btn-warning";
$status_link = $test->status ? "stop.php" : "start.php";
$warn = "Are you sure you want to " . ($test->status ? "Stop" : "Start") . " the test {$test->tname} ({$test->tcode})?";


$result = new Result($db,$test->tcode);
$count = $result->countAll();
$count_text = "<h3 class='text-center'><b>{$count}</b> Student(s) are registered for <b>{$test->tname}</b> ({$test->tcode})</h3>";
$result_link = "read_result.php?tcode={$test->tcode}";
$result_text = "<span class='glyphicon glyphicon-new-window'></span> Check Result / Registrations";

$delete_warn = "WARNING: Are you sure you want to DELETE Test {$test->tname} ({$test->tcode})? This will delete all records for this test, including codes submitted by the students and the result of this test. THIS ACTION CANNOT BE UNDONE.";
$delete_text = "<span class='glyphicon glyphicon-trash'></span> Delete Test";

?>

<div class='col-md-12'>
    <table class='table table-hover'>

        <form method="post" action="rename.php"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-3'><b>Test Name</b></td>
            <td class='col-md-7'><?php echo $test->tname; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block'><b>Rename</b></button></td>
        </tr>
        </form>

        <form method="post" action="regenerate_key.php"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-3'><b>Registration Key</b></td>
            <td class='col-md-7'><?php echo $test->reg_key; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $reset_confirm; ?>')"><b>Regenerate</b></button></td>
        </tr>
        </form>

        <form method="post" action="change_problem.php"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-3'><b>Problem Code</b></td>
            <td class='col-md-7'><?php echo $pcode; ?></td>
            <td><button type="submit" class='btn btn-danger btn-block active' onclick="return confirm('<?php echo $change_warn; ?>')"><b><?php echo $change_text; ?></b></button></td>
        </tr>
        </form>

        <tr>
            <td class='col-md-3'><b>Problem Details</b></td>
            <td class='col-md-7'><?php echo $pname . $ready; ?></td>
            <td><a href="<?php echo $detail_link; ?>" class='btn btn-primary btn-block' target="_blank" onclick="return confirm('<?php echo $detail_note; ?>')" ><?php echo $detail_text; ?></button></td>
        </tr>

        <form method="post" action="count_submission.php?tcode=<?php echo $test->tcode; ?>" target="count_frame">
        <tr>
            <td class='col-md-3'><b><big>Submission Count</big></b></td>
            <td><iframe name="count_frame" src="count_submission.php?tcode=<?php echo $test->tcode; ?>" style="border:none;height:30px;width:100%"></iframe></td>
            <td><button type="submit" class='btn btn-primary btn-block'><b>Update</b></button></td>
        </tr>
        </form>

        <form method="post" action="<?php echo $status_link;?>"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-3'><h3><b>Test Status</b></h3></td>
            <td class='col-md-7'><h3><b><?php echo $status; ?></b></h3></td>
            <td><button type="submit" class="<?php echo $btn; ?>" onclick="return confirm('<?php echo $warn; ?>')"><b><?php echo $new_state; ?></b></td>
        </tr>
        </form>

    </table>

    <p>
        <div class="well well-sm"><?php echo $count_text; ?>
        <a href="<?php echo $result_link; ?>" class="btn btn-success btn-lg btn-block" target="_blank"><b><?php echo $result_text?></b></a>
        </div>
    </p>

    <form method="post" action="delete.php"> <?php echo $tcode_ip;?>
    <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="return confirm('<?php echo $delete_warn; ?>')"><b><?php echo $delete_text?></b></button>
    </form>
    <br /><br />
</div>

<?php
include_once "../layout_foot.php";
?>