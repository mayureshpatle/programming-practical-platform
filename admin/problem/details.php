<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/problem.php';
include_once 'md.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_GET['pcode']))
{
    try
    {
        $problem = new Problem($db);
        $problem->pcode = $_GET['pcode'];
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
$page_title = "Problem: {$problem->pcode}";

// include page header HTML
include_once "../layout_head.php";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

$owner_id = empty($problem->owner) ? "deleted" : $problem->owner;
if($owner_id == $_SESSION['id']) $owner_id .= ", YOU";

$created = strtotime($problem->created);
$created = date("d-m-Y h:i:s a", $created);

$editor_id = empty($problem->last_editor) ? "deleted" : $problem->last_editor;
if($editor_id == $_SESSION['id']) $editor_id .= ", YOU";

$last_edit = strtotime($problem->last_edit);
$last_edit = date("d-m-Y h:i:s a", $last_edit);

$c_status = $problem->c ? ( "<b>Enabled</b> " . ( $problem->c_ready ? "<big><span class='label label-lg label-success'>Ready</span></big>" : "<big><span class='label label-lg label-danger'>Not Ready</span></big>" )) : "<b>Disabled</b>";
$cpp14_status = $problem->cpp14 ? ( "<b>Enabled</b> " . ( $problem->cpp14_ready ? "<big><span class='label label-lg label-success'>Ready</span></big>" : "<big><span class='label label-lg label-danger'>Not Ready</span></big>" )) : "<b>Disabled</b>";
$py3_status = $problem->py3 ? ( "<b>Enabled</b> " . ( $problem->py3_ready ? "<big><span class='label label-lg label-success'>Ready</span></big>" : "<big><span class='label label-lg label-danger'>Not Ready</span></big>" )) : "<b>Disabled</b>";
$java_status = $problem->java ? ( "<b>Enabled</b> " . ( $problem->java_ready ? "<big><span class='label label-lg label-success'>Ready</span></big>" : "<big><span class='label label-lg label-danger'>Not Ready</span></big>" )) : "<b>Disabled</b>";

$c_btn = $problem->c ? "class='btn btn-md btn-block btn-info active'" : "class='btn btn-md btn-block btn-default active'";
$cpp14_btn = $problem->cpp14 ? "class='btn btn-md btn-block btn-info active'" : "class='btn btn-md btn-block btn-default active'";
$py3_btn = $problem->py3 ? "class='btn btn-md btn-block btn-info active'" : "class='btn btn-md btn-block btn-default active'";
$java_btn = $problem->java ? "class='btn btn-md btn-block btn-info active'" : "class='btn btn-md btn-block btn-default active'";

include "action_response.php";

$get = "?pcode={$problem->pcode}";

$reset_confirm = "WARNING: This action cannot be undone, and you will need to make changes in drivers of all languages. Do you still want to regenerate the Judge Key?";

//description
$fpath = "../../problems/{$problem->pcode}/description.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $description = "";
    $description = @fread($desc,$size);
    fclose($desc);
}
else $description = "<p class='text-danger'><b> Problem Description is not set.<br /><small>Click on Edit to set the problem description.</small></b></p>";
?>

<div class='col-md-12'>
    <table class='table table-hover'>
        <form method="post" action="rename.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Problem Name</b></td>
            <td class='col-md-8'><?php echo $problem->pname; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block'><b>Rename</b></button></td>
        </tr>
        </form>

        <form method="post" action="regenerate_key.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Judge Key</b></td>
            <td class='col-md-8'><?php echo $problem->judge_key; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $reset_confirm; ?>')"><b>Regenerate</b></button></td>
        </tr>
        </form>

        <tr>
            <td class='col-md-2'><b>Created By</b></td>
            <td class='col-md-8'><?php echo $problem->owner_name . " (" . $owner_id . ")"; ?></td>
            <td><a href='#' class='btn btn-sm btn-default btn-block' disabled='disabled'><b><?php echo $created; ?></b></div></td>
        </tr>

        <tr>
            <td class='col-md-2'><b>Modified By</b></td>
            <td class='col-md-8'><?php echo $problem->editor_name. " (" . $editor_id. ")"; ?></td>
            <td><a href='#' class='btn btn-sm btn-default btn-block' disabled='disabled'><b><?php echo $last_edit; ?></b></div></td>
        </tr>

        <form method="post" action="c/show.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>C Status</b></td>
            <td class='col-md-8'><?php echo $c_status; ?></td>
            <td><button <?php echo $c_btn; ?> type="submit"><b>Details</b></button></td>
        </tr>
        </form>

        <form method="post" action="cpp14/show.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>CPP14 Status</b></td>
            <td class='col-md-8'><?php echo $cpp14_status; ?></td>
            <td><button <?php echo $cpp14_btn; ?> type="submit"><b>Details</b></button></td>
        </tr>
        </form>

        <form method="post" action="py3/show.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Python 3 Status</b></td>
            <td class='col-md-8'><?php echo $py3_status; ?></td>
            <td><button <?php echo $py3_btn; ?> type="submit"><b>Details</b></button></td>
        </tr>
        </form>

        <form method="post" action="java/show.php"> <?php echo $pcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Java Status</b></td>
            <td class='col-md-8'><?php echo $java_status; ?></td>
            <td><button <?php echo $java_btn; ?> type="submit"><b>Details</b></button></td>
        </tr>
        </form>

        <tr><td></td><td></td><td></td></tr>
    </table>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b>Problem Description</b></h3>
        <div class='well'>
            <big>
                <?php echo md_format($description); ?>
            </big>
        </div>

        <br />

        <form method="post" action="edit_desc.php"> <?php echo $pcode_ip;?>        
        <button type="submit" class="btn btn-primary btn-md active" onclick="return confirm('Are you sure you want to edit the description of this problem?')">
            <b><span class='glyphicon glyphicon-edit'></span> Edit </b>
        </button>
        </form>
    </div>
</div>

<?php
include_once "../layout_foot.php";
?>