<?php
// core configuration
include_once "../../../config/core.php";
 
// check if logged in as admin
include_once "../../login_checker.php";
 
// include classes
include_once '../../../config/database.php';
include_once '../../../objects/problem.php';
include_once '../../../objects/judge.php';
include_once 'md.php';

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
$page_title = "Problem: {$problem->pcode} (Java Config -> Testing)";

// include page header HTML
include_once "../../layout_head.php";
include_once "../../../libs/js/utils.php";

//for using in submission form
$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";
$submit = "Are you sure you want to submit this code? This will overwrite the previously saved code (if any).";

$reset_code = true;
$done = false;
$error = false;

$time_limit = $problem->c_time;

//description
$fpath = "../../../problems/{$problem->pcode}/description.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $description = "";
    $description = @fread($desc,$size);
    fclose($desc);
}
else $description = "";
$description .= "\n\n";

//language specific description
$fpath = "../../../problems/{$problem->pcode}/drivers/java/description.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $description .= @fread($desc,$size);
    fclose($desc);
}

//locked head code
$fpath = "../../../problems/{$problem->pcode}/drivers/java/locked_head.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $locked_head = "";
    $locked_head = @fread($desc,$size);
    $locked_head = "```java\n" . $locked_head . "\n```";
    fclose($desc);
}
else $locked_head = "";

//locked tail code
$fpath = "../../../problems/{$problem->pcode}/drivers/java/locked_tail.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $locked_tail = "";
    $locked_tail = @fread($desc,$size);
    $locked_tail = "```java\n" . $locked_tail . "\n```";
    fclose($desc);
}
else $locked_tail = "";

//saved solution
$fpath = "../../../problems/{$problem->pcode}/drivers/java/testing/{$_SESSION['id']}.txt";
$soln = @fopen($fpath,"a");
@fclose($soln);
$size = filesize($fpath);
if($size)
{
    $soln = fopen($fpath,"r");
    $old_code = @fread($soln, $size );
    fclose($soln);
}
else $old_code = "";

if(isset($_POST['code']))
{
    //submitted code
    $code = $_POST['code'];
    $fpath = "../../../problems/{$problem->pcode}/drivers/java/testing/{$_SESSION['id']}.txt";
    $soln = @fopen($fpath,"w");
    @fwrite($soln,$code);
    @fclose($soln);

    //head code
    $fpath = "../../../problems/{$problem->pcode}/drivers/java/head.txt";
    $size = filesize($fpath);
    if($size)
    {
        $desc = fopen($fpath,"r");
        $head = "";
        $head =  @fread($desc,$size);
        @fclose($desc);
    }
    else $head = "";

    //tail code
    $fpath = "../../../problems/{$problem->pcode}/drivers/java/tail.txt";
    $size = filesize($fpath);
    if($size)
    {
        $desc = fopen($fpath,"r");
        $tail = "";
        $tail = @fread($desc,$size);
        $tail = $tail;
        fclose($desc);
    }
    else $tail = "";

    $source_code = $head . "\n" . $code . "\n" . $tail;

    echo "<div class='col-md-12'>
            <div class='alert alert-info'>
                SUBMISSION STATUS:<br />";

    $judge = new Judge($oj_url, $oj_client_secret);
    $response = $judge->submit_problem($source_code, "JAVA", $problem);

    echo $response->status;

    if($response->compile_status!="") echo "<br>COMPILE STATUS: {$response->compile_status}";

    echo "</div></div>";

    $reset_code = false;
}

//code
if($reset_code)
{
    $code = $old_code;
}
else
{
    $problem->submitted($response);
}
include_once "submit_form.php";

include_once "../../layout_foot.php";
?>