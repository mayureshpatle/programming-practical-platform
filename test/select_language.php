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

//for markdowm
include_once 'md.php';

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

if(!$go_ahead) header("Location: {$home_url}");

if(!$test->status) header("Location: {$home_url}test/details.php?tcode={$test->tcode}&action=not_live");

if(!$test->initProblem(new Problem($db))) header("Location: {$home_url}test/details.php?tcode={$test->tcode}&action=error");

// set page title
$page_title = "Test: {$test->tname} ({$test->tcode})";

// include page header HTML
include_once "../layout_head.php";

if($test->initResult(new Result($db,$test->tcode)))
{
    $tcode_ip = "<input type='hidden' name='tcode' value='{$test->tcode}' />";

    echo "<div class='col-md-12'>
            <table class='table'>
                <tr>
                    <th><h1>Problem: <span class='text-muted'>{$test->getPname()}</span></h1></th>
                </tr>
            </table>
        </div>";

    $langs = $test->getLangs();
    
    echo "<form method='post' action='live.php' class='col-md-12'>
            {$tcode_ip}
            <label class='btn btn-lg' disabled><b>Select Language: </b></label>

            <select name='lang' class='btn btn-lg btn-primary'>";
    
            foreach($langs as $lang)
            {
                echo "<option value='{$lang}'><b>{$lang}</b></option>";
            }

    echo    "</select>
            <button type='submit' class='btn btn-success btn-lg'><b><span class='glyphicon glyphicon-chevron-right'></span> Continue</b></button>
        </form>";
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