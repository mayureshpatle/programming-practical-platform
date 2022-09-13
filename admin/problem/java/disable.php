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

$page_title = "Problem: {$problem->pcode} (Java Config)";

include_once "../../layout_head.php";

$problem->java = 0;

echo "<div class='col-md-12'>";

if($problem->update())
{
    echo "<div class='alert alert-success'>This language is now disabled.</div>";
}
else
{
    echo "<div class='alert alert-danger'> Some error occured while disabling this language.</div>";
}

echo "<form method='post' action='show.php'>
    <input name='pcode' type='hidden' value='{$problem->pcode}' />
        <button class='btn btn-danger' type='submit'><b><span class='glyphicon glyphicon-log-out'></span> Close</b></button>
    </form>
    </div>";

include_once "../../layout_foot.php"
?>