<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
include_once '../../objects/problem.php';

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

$problem = new Problem($db);
$problem->pcode = $test->pcode;
$problem->pcodeExists();
$problem->setReady();

$_POST = array();

if($problem->ready)
{
    $test->status = 1;

    $action = $test->setStatus() ? "started" : "error";
    header("Location: details.php?tcode={$test->tcode}&action={$action}");
}
else
{
    header("Location: details.php?tcode={$test->tcode}&action=not_ready");
}

?>