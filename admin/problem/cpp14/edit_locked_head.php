<?php
// core configuration
include_once "../../../config/core.php";
 
// check if logged in as admin
include_once "../../login_checker.php";
 
// include classes
include_once '../../../config/database.php';
include_once '../../../objects/problem.php';

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
$page_title = "Problem: {$problem->pcode} (C++14 Config)";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

$reset_desc = true;
$no_change = false;
$done = false;
$error = false;

$fpath = "../../../problems/{$problem->pcode}/drivers/cpp14/locked_head.txt";
$size = filesize($fpath);
if($size)
{
    $locked = fopen($fpath,"r");
    $old_code = @fread($locked, $size );
    fclose($locked);
}
else $old_code = "";

if(isset($_POST['code']))
{
    $code = $_POST['code'];
    if($old_code != $code)
    {
        $problem->flag = 1 - $problem->flag;
        if($problem->update())
        {
            $locked = @fopen($fpath, "w");
            @fwrite($locked, $code);
            @fclose($locked);
            $_POST = array();
            $done = true;
        }
        else 
        {
            $error = true;
            $reset_desc = false;
        }
    }
    else 
    {
        $no_change = true;
        $reset_desc = false;
    }
}

// include page header HTML
include_once "../../layout_head.php";
include_once "../../../libs/js/utils.php";

//code
if($done)
{
    echo "<div class='col-md-12'>
            <div class='alert alert-success'>
                Locked Head Code has been updated successfully.<br />
                Ready status for the language was not changed.
            </div>
        </div>
        
        <form class='col-md-2' action='show.php' method='post' > {$pcode_ip}
            <button class='btn btn-block btn-danger' type='submit'><b>
                <span class='glyphicon glyphicon-log-out'></span> Close</b>
            </button>
        </form>";    
}
else
{
    $save = "Are you sure you want to update the Locked Head Code? This will overwrite the previously saved code (if any). This will not change the Ready Status of this language.";
    $abort = "Do you really want to abort editing the Locked Head Code? No changes will be made in the code.";

    if($reset_desc)
    {
        $code = $old_code;
    }
    else if($no_change)
    {
        echo "<div class='col-md-12'>
                <div class='alert alert-warning'>
                    You didn't make any change in the code.
                    Press Cancel button if you want to keep the code unchanged.
                </div>
            </div>";
    }
    else
    {
        echo "<div class='col-md-12'>";
            echo "<div class='alert alert-danger'> Some error occurred, please try again or cancel this action.</div>";
        echo "</div>";
    }

    include_once "locked_head_form.php";
}

include_once "../../layout_foot.php";
?>