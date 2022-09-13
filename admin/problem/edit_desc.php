<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/problem.php';

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
$page_title = "Problem: {$problem->pcode}";

$pcode_ip = "<input name='pcode' type='hidden' value='{$problem->pcode}' />";

$reset_desc = true;
$no_change = false;

$fpath = "../../problems/{$problem->pcode}/description.txt";
$size = filesize($fpath);
if($size)
{
    $desc = fopen($fpath,"r");
    $old_description = @fread($desc, $size );
    fclose($desc);
}
else $old_description = "";

if(isset($_POST['desc']))
{
    $description = $_POST['desc'];
    if($old_description != $description)
    {
        $problem->flag = 1 - $problem->flag;
        if($problem->update()) 
        {
            $desc = @fopen($fpath, "w");
            @fwrite($desc, $description);
            @fclose($desc);
            $_POST = array();
            header("Location: details.php?pcode={$problem->pcode}&action=desc_updated");
        }
        else 
        {
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
include_once "../layout_head.php";
include_once "../../libs/js/utils.php";

//description
if($reset_desc)
{
    $description = $old_description;
}
else if($no_change)
{

    echo "<div class='col-md-12'>
            <div class='alert alert-warning'>
                You didn't make any change in the description.
                Press Cancel button if you want to keep the description unchanged.
            </div>
        </div>";
}
else
{
    echo "<div class='col-md-12'>";
        echo "<div class='alert alert-danger'> Some error occurred, please try again or cancel this action.</div>";
    echo "</div>";
}

$save = "Are you sure you want to update the problem description? This will overwrite the previously saved description (if any). This action cannot be undone.";
$abort = "Do you really want to abort editing the problem description? No changes will be made in the problem description.";

?>

<form action='edit_desc.php' method='post'>  <?php echo $pcode_ip;?>
<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Problem Description</b></h4>
        <textarea id='textarea' class='form-control' placeholder="Enter Problem Description Here. &#10;Markdown formatting is supported." rows=25 name='desc' style='resize: none;' spellcheck='false' ><?php echo $description; ?></textarea>
        <br />
    </div>
</div>

    <div class="col-md-2">
        <p><button type='submit' class='btn btn-primary btn-block' onclick="return confirm('<?php echo $save; ?>')">
            <b><span class='glyphicon glyphicon-floppy-disk'></span> Save</b>
        </button></p>
    </div>
        
    <div class="col-md-2">
        <p><a href='details.php?pcode=<?php echo $problem->pcode; ?>&action=cancelled' class='btn btn-danger btn-block' onclick="return confirm('<?php echo $abort; ?>')">
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>

    <br />
    <br />
    <br />

</form>

<script> enableTab('textarea'); </script>

<?php
include_once "../layout_foot.php";
?>