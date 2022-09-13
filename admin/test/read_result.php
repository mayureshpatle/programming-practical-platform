<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
include_once '../../objects/result.php';


$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_GET['tcode']))
{
    try
    {
        $test = new Test($db);
        $test->tcode = $_GET['tcode'];
        if($test->tcodeExists())
        {
            $result = new Result($db, $test->tcode);
            $go_ahead = true;
        }
    }
    catch(Exception $e)
    {
        //damnnn...
    }
}

if(!$go_ahead) header("Location: {$home_url}");
 
// set page title
$page_title = "{$test->tname} ({$test->tcode}) Result";
 
// include page header HTML
include_once "../layout_head.php";
 
echo "<div class='col-md-12'>";

echo "<p><a href='download_result.php?tcode={$test->tcode}' class='btn btn-lg btn-primary' target='_blank'>
        <b><span class='glyphicon glyphicon-save'></span> Download Result</a></b></p>";
 
    // read all problems from the database
    $stmt = $result->readAll($from_record_num, $records_per_page);
 
    // count retrieved problems
    $num = $stmt->rowCount();
 
    // to identify page for paging
    $page_url="read_result.php?tcode={$test->tcode}&";
 
    // include problems table HTML template
    include_once "read_result_template.php";
 
echo "</div>";
 
// include page footer HTML
include_once "../layout_foot.php";
?>
