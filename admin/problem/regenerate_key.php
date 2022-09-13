<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/problem.php';
include_once '../../libs/php/utils.php';

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

$utils = new Utils();
$problem->judge_key = $utils->getToken(16);
$problem->c_ready = 0;
$problem->cpp14_ready = 0;
$problem->py3_ready = 0;
$problem->java_ready = 0;
$action = $problem->update() ? "regenerated" : "regeneration_failed";
$_POST = array();
header("Location: details.php?pcode={$problem->pcode}&action={$action}");

?>