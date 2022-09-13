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
include_once "../libs/js/utils.php";

$lang_ip = "";

if($test->initResult(new Result($db,$test->tcode)))
{
    include_once "test_response.php";

    $tcode_ip = "<input type='hidden' name='tcode' value='{$test->tcode}' />";

    echo "<div class='col-md-12'>
            <table class='table'>
                <tr>
                    <th><h1>Problem: <span class='text-muted'>{$test->getPname()}</span></h1></th>
                </tr>
            </table>
        </div>";

    if(!isset($_POST['lang']))
    {
        echo "<form method='post' action='select_language.php' class='col-md-12'>
                {$tcode_ip};
                <button type='submit' class='btn btn-primary btn-lg'><b>Select a language to continue <span class='glyphicon glyphicon-circle-arrow-right'></span></b></button>
            </form>";
    }

    else
    {
        $test->lang = $_POST['lang'];
        $lang_ip = "<input type='hidden' name='lang' value='{$test->lang}' />";

        $lang_warn = "Ensure that you have saved the code for current language, otherwise you will lose the typed code for current language.";

        $description = md_format($test->getDesc());

        $locked_head = md_format($test->getLockedHead());

        $locked_tail = md_format($test->getLockedTail());

        $time_limit = $test->timeLimit();
        
        $code = $test->prevCode();

        include_once "submit_form.php";

        echo "</div>";
    }
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