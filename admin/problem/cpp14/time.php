<?php
// core configuration
include_once "../../../config/core.php";
 
// check if logged in as admin
include_once "../../login_checker.php";
 
// include classes
include_once '../../../config/database.php';
include_once '../../../objects/problem.php';

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
$page_title = "Problem: {$problem->pcode} (C++14 Config)";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

$error = false;
$done = false;
$same_time = false;

if(isset($_POST['cpp14_time']))
{
    if($problem->cpp14_time != $_POST['cpp14_time'])
    {
        $problem->cpp14_time = $_POST['cpp14_time'];
        $problem->cpp14_ready = 0;
        $error = !$problem->update();
        if(!$error) $_POST = array();
        $done = true;
    }
    else $same_time = true;
}

// include page header HTML
include_once "../../layout_head.php";
include_once "../../../libs/js/utils.php";

if($done)
{
    echo "<div class='col-md-12'>
            <div class='alert alert-success'> Time Limit for this language has been updated successfully.<br />
            Status is now set to 'Not Ready for this language'.<br />
            Submit a working solution to set the status 'Ready'.
            </div>
        
        <form method='post' action='show.php'> {$pcode_ip}
            <button class='btn btn-danger' type='submit'><b><span class='glyphicon glyphicon-log-out'> Close</span></button>
        </form>
        </div>";
}
else
{
    if($same_time)
    {
        echo "<div class='col-md-12'>
            <div class='alert alert-warning'> You didn't chnage the time limit. <br />
            Press Cancel button if you want to abort changing the time limit.
            </div>
        </div>";
    }
    else if($error)
    {
        echo "<div class='col-md-12'>
            <div class='alert alert-warning'> Some error occured while changing the time limit for this language.
            </div>
        </div>";
    }
    include_once "time_form.php";
}

include_once "../../layout_foot.php";
?>