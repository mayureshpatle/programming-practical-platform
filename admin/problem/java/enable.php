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

$problem->java = 1;

echo "<div class='col-md-12'>";

if($problem->update())
{
    echo "<div class='alert alert-success'>This language is now enabled.<br />
            Set the head code, tail code, locked code and language specific description for this language.<br />
            Kindly ensure that the status is 'Ready' by submitting 100% accurate solution in this language.<br />
            Output of the program should only contain lines in format 'Judge_Key:Score' <i>(without quotes)</i>.<br />
            Final Score will be sum of scores printed on each line. Kindly ensure that this sum should be 100 for accurate solutions.<br />
            If anything else is printed on the output, or if sum is greater than 100 then a score of 0 will be given.
        </div>";
}
else
{
    echo "<div class='alert alert-danger'> Some error occured while enabling this language.</div>";
}

echo "<form method='post' action='show.php'>
    <input name='pcode' type='hidden' value='{$problem->pcode}' />
        <button class='btn btn-danger' type='submit'><b><span class='glyphicon glyphicon-log-out'></span> Close</b></button>
    </form>
    </div>";

include_once "../../layout_foot.php"
?>