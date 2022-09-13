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


$error = false;

// get database connection
$database = new Database();
$db = $database->getConnection();
if(isset($_POST['tcode']))
{
    try
    {
        $test = new Test($db);
        $test->tcode = $_POST['tcode'];
        if(!$test->tcodeExists())
        {
            $error = true;
        }
    }
    catch(Exception $e)
    {
        //echo "damnnn...";
    }
}

$live = (bool)$test->status;

$lang = false;

if(!$error && $live)
{
    if(isset($_POST['lang'])) $test->lang = $lang = $_POST['lang'];
}
else $lang=true;

$fetch_error = true;

if($test->initResult(new Result($db,$test->tcode))) 
{
    if($live && $lang) $error &= $test->saveCode($_POST['code']);
    else $error = true;
    $fetch_error = false;
}
else $error = true;

include_once "frame_head.php";

if($error)
{
    echo "<div class='alert alert-danger'>";
    if(!$live) echo "<h1>⛔</h1> This test is no longer active. Contact your teacher if you think this is a mistake. <br /> Click on the End Test button to exit.";
    else if($lang==false) echo "No Language Selected!";
    else echo "<b><span class='glyphicon glyphicon-warning-sign'></span> Some error occurred while saving the code. Please try again.</b>";
    echo "</div>";
}
else
{
    echo "<div class='alert alert-success'>
            Code Saved Successfully.
        </div>";
}


include_once "frame_foot.php";
?>