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
include_once '../objects/judge.php';


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

if(!$error && $live)
{
    if(isset($_POST['lang'])) $test->lang = $lang = $_POST['lang'];
    else $lang=false;
}
else $lang=true;

$fetch_error = true;

if(!$error && $test->initResult(new Result($db,$test->tcode))) 
{
    if($live && $lang) $error &= $test->saveCode($_POST['code']);
    else $error = true;
    $fetch_error = false;
}
else $error = true;

$response = false;

if($test->initJudge(new Judge($oj_url, $oj_client_secret), new Problem($db))) $response = $test->submit($_POST['code']);

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

if($response == false)
{
    echo "<div class='alert alert-danger'>
    <b><span class='glyphicon glyphicon-warning-sign'></span> Some error occurred while evaluating the code. Please try again.</b>";
    echo "</div>";
}
else
{
    $score = (int)$response->score;
    $score_status = "<span class='glyphicon glyphicon-" . ($score > 0 ? "ok" : "remove") . "'></span>";
    $score_message = "Your submission got a score of <b>{$score}</b> {$score_status}";
    if($score == 100) $score_message =  "<big>✨</big> {$score_message}";
    $score_message = "<h4>{$score_message}</h4>";
    $score_alert = !$fetch_error && $score>0 ? ($score == 100 ? "'alert alert-success'" : "'alert alert-warning'") : "'alert alert-danger'";
    echo "<div class={$score_alert}>
            {$score_message}
        </div>";

    $line = "<table class='table'><tr><td></td></tr></table>";

    if($score!=100)
    {

        echo $line;

        echo "<h3>EVALUATION LOG:\n</h3>";
        echo $response->status;

        echo "<h3>VERDICT: You code doesn't work correctly for all the test cases.</h3>";

        if($response->format_error || $response->invalid_score) echo "<h3>VERDICT: Invalid Output</h3>";

        if($response->compile_status!="")
        {
            echo $line;
            echo "<h3>COMPILE STATUS:\n</h3>";
            echo $response->compile_status;
        }
        if($response->error!="")
        {
            echo $line;
            echo "<h3>ERROR DETAILS:\n</h3>";
            echo $response->error;
        }
    }
}



include_once "frame_foot.php";
?>