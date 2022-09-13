<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/test.php';
include_once '../objects/result.php';
include_once '../objects/access.php';
include_once '../libs/php/utils.php';

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

if($go_ahead)
{
    try
    {
        $result = new Result($db, $test->tcode);
        $result->user_id = $_SESSION['id'];
        if(!$result->recordExists()) $go_ahead = false;
    }
    catch(Exception $e)
    {
        $go_ahead = false;
    }
}

if($go_ahead)
{
    try
    {
        $access = new Access($db);
        $access->user_id = $_SESSION['id'];
        $access->tcode = $test->tcode;
        $access->utils = new Utils();
        if(!$access->accessExists()) $go_ahead = false;
    }
    catch(Exception $e)
    {
        $go_ahead = false;
    }
}

if(!$go_ahead) header("Location: {$home_url}");

$flag = $result->deleteRecord();

$flag = $access->deregister() && $flag;

$action = $flag ? "deregistered" : "deregister_failure";
$_POST = array();
header("Location: ../my_tests.php?tcode={$test->tcode}&action={$action}");

?>