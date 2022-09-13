<?php
// core configuration
include_once "../../../config/core.php";
 
// check if logged in as admin
include_once "../../login_checker.php";
 
// include classes
include_once '../../../config/database.php';
include_once '../../../objects/problem.php';
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
$page_title = "Problem: {$problem->pcode} (Python 3 Config)";

// include page header HTML
include_once "../../layout_head.php";

include "action_response.php";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

$status = ( $problem->py3 ?  "<b>Enabled</b> " : "<b>Disabled</b> " ) . ( $problem->py3_ready ? "<big><span class='label label-lg label-success'>Ready</span></big>" : "<big><span class='label label-lg label-danger'>Not Ready</span></big>" );
$btn = $problem->py3 ? "class='btn btn-md btn-block btn-danger'" : "class='btn btn-md btn-block btn-primary'";
$new_status = $problem->py3 ? "Disable" : "Enable";
$action = $problem->py3 ? "disable.php" : "enable.php";
$status_warn = "Are you sure you want to {$new_status} this language?";

//description
$fpath = "../../../problems/{$problem->pcode}/drivers/py3/description.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $description = "";
    $description = @fread($desc,$size);
    fclose($desc);
}
else $description = "<p class='text-danger'><b> Language Specific Description is not set.<br /><small>Click on Edit to set the Language Specific Description.</small></b></p>";

//head code
$fpath = "../../../problems/{$problem->pcode}/drivers/py3/head.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $head = "";
    $head = "```py\n". @fread($desc,$size) . "\n```";
    @fclose($desc);
}
else $head = "<p class='text-danger'><b> Head Code is not set for this language.<br /><small>Click on Edit to set the Head Code.</small></b></p>";

//tail code
$fpath = "../../../problems/{$problem->pcode}/drivers/py3/tail.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $tail = "";
    $tail = @fread($desc,$size);
    $tail = "```py\n" . $tail . "\n```";
    fclose($desc);
}
else $tail = "<p class='text-danger'><b> Tail Code is not set for this language.<br /><small>Click on Edit to set the Tail Code.</small></b></p>";

//locked head code
$fpath = "../../../problems/{$problem->pcode}/drivers/py3/locked_head.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $locked_head = "";
    $locked_head = @fread($desc,$size);
    $locked_head = "```java\n" . $locked_head . "\n```";
    fclose($desc);
}
else $locked_head = "<p class='text-danger'><b> Locked Head Code is not set for this language.<br /><small>Click on Edit to set the Locked Code.</small></b></p>";

//locked tail code
$fpath = "../../../problems/{$problem->pcode}/drivers/py3/locked_tail.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $locked_tail = "";
    $locked_tail = @fread($desc,$size);
    $locked_tail = "```java\n" . $locked_tail . "\n```";
    fclose($desc);
}
else $locked_tail = "<p class='text-danger'><b> Locked Tail Code is not set for this language.<br /><small>Click on Edit to set the Locked Code.</small></b></p>";


?>

<div class='col-md-12'>
    <table class='table table-hover'>
        <form method="post" action="show.php?action=judge_key"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Judge Key</b></td>
            <td class='col-md-8'><?php echo $problem->judge_key; ?></td>
            <td><button class='btn btn-block btn-primary' type="submit"><b>Info</b></button></td>
        </tr>
        </form>

        <form method="post" action="time.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Time Limit</b> (in seconds)</td>
            <td class='col-md-8'><?php echo $problem->py3_time; ?></td>
            <td><button class='btn btn-block btn-primary' type="submit"><b>Change</b></button></td>
        </tr>
        </form>


        <form method="post" action="<?php echo $action; ?>"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Status</b></td>
            <td class='col-md-8'><?php echo $status; ?></td>
            <td><button <?php echo $btn; ?> type="submit" onclick="return confirm('<?php echo $status_warn; ?>')" ><b><?php echo $new_status; ?></b></button></td>
        </tr>
        </form>
    </table>
</div>

<div class='col-md-12'>
<p>
<form method="post" action="submit.php"> <?php echo $pcode_ip;?>
    <button type="submit" class="btn btn-success btn-md btn-block">
        <b><big><span class='glyphicon glyphicon-send'></span> Submit and Test</big></b>
    </button>
</form>
</p>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Language Specific Description</b></h3>
        <div class='well'>
            <big>
                <?php echo md_format($description); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_desc.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the description of this language?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Head Code</b><small> <i>(not visible to student)</i></small></h3>
        <div class='well'>
            <big>
                <?php echo md_format($head); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_head.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the Head Code for this language?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Tail Code <small> <i>(not visible to student)</i></small></b></h3>
        <div class='well'>
            <big>
                <?php echo md_format($tail); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_tail.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the Tail Code for this language?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Locked Head Code</b> <small><i>(function header or class header to display, visible to student)</i></small></h3>
        <div class='well'>
            <big>
                <?php echo md_format($locked_head); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_locked_head.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the Locked Head Code for this language?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Locked Tail Code</b> <small><i>(final brackets of function, class or struct to display, visible to student)</i></small></h3>
        <div class='well'>
            <big>
                <?php echo md_format($locked_tail); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_locked_tail.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the Locked Tail Code for this language?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<?php
// include page footer HTML
include_once '../../layout_foot.php';
?>