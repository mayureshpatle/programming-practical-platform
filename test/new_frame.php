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
if(isset($_GET['tcode']))
{
    try
    {
        $test = new Test($db);
        $test->tcode = $_GET['tcode'];
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

$live = $test->status;

if(!$test->initResult(new Result($db,$test->tcode))) $error = true;

$fetch_error = $error;

include_once "frame_head.php";

if($error)
{
    echo "<div class='alert alert-danger'>";
    if(!$live) echo "<h1>⛔</h1> This test is no longer active. Contact your teacher if you think this is a mistake. <br /> Click on the End Test button to exit.";
    else echo "<b><span class='glyphicon glyphicon-warning-sign'></span> Something went wrong!</b>";
    echo "</div>";
}

include_once "frame_foot.php";
?>