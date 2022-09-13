<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
include_once '../../objects/result.php';
include_once '../../objects/problem.php';
include_once '../../libs/php/utils.php';

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
        if($test->tcodeExistsAdmin())
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
$page_title = "Test: {$test->tcode}";

$done = true;
$test->utils = new Utils();
$result = new result($db, $test->tcode);

$result->deleteTable() && $done;
$done = $test->deleteTest() && $done;

$action = $done ? "deleted" : "error";
$link = "../read_tests.php?tcode={$test->tcode}&tname={$test->tname}&action={$action}";
?>

<script>
    location.replace("<?php echo $link; ?>");
</script>