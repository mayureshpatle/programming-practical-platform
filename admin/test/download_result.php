<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
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
        if($test->tcodeExists())
        {
            $result = new Result($db, $test->tcode);
            $go_ahead = true;
        }
    }
    catch(Exception $e)
    {
        //damnnn...
    }
}

if(!$go_ahead) die("<h2>Some Error Occurred</h2>");

$result->generateFile();

$file = "../../tests/{$test->tcode}/result_{$test->tcode}_{$_SESSION['id']}.csv"; 

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"". basename($file) ."\""); 

readfile ($file);
exit();
 
?>