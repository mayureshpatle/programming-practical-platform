<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/test.php';
include_once '../objects/problem.php';
include_once '../objects/result.php';

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
        if($test->tcodeExists())
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
$page_title = "Test: {$test->tname} ({$test->tcode})";

// include page header HTML
include_once "../layout_head.php";

$result = new Result($db,$test->tcode);
$result->user_id = $_SESSION['id'];
if($result->recordExists())
{
    $tcode_ip = "<input name='tcode' type='hidden' value='{$test->tcode}' />";

    $edit_roll_confirm = "Are you sure you want to edit your roll number for this test.";

    $update_name_confirm = "Your name will be set to {$_SESSION['name']} (i.e. your name as saved in your profile currently).";
    
    $status = $test->status ? "<b class='text-success'><span class='glyphicon glyphicon-time'></span> Live</b>" : "<b class='text-danger'><span class='glyphicon glyphicon-off'></span> Closed</b>";
    $disable = $test->status ? "" : "disabled='disabled'";
    $test_link = "live.php";

    if($result->submitted == NULL) $submitted_on = "No Submissions";
    else
    {
        $submitted_on = strtotime($result->submitted);
        $submitted_on = date("d-m-Y h:i:s a", $submitted_on);
    }

    $delete_warn = "WARNING: Are you sure you want to deregister from {$test->tname} ({$test->tcode})? This will delete all your records for this test, including submitted codes (if any). THIS ACTION CANNOT BE UNDONE.";
    $delete_text = "<span class='glyphicon glyphicon-trash'></span> Deregister from this test";

    include_once "action_response.php";
    include_once "detail_table.php";
}
else
{
    echo "<div class='col-md-12'>
            <div class='alert alert-danger'>
                <h3>You are not registered for this test.</h3><br />
                <a href='../test_registration.php' class='btn btn-lg btn-danger'><b>Click here to register</b></a>
            </div>
        </div>";
}
include_once "../layout_foot.php";
?>