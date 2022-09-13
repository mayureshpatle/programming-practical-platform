<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
include_once "login_checker.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/test.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$test = new Test($db);
 
// set page title
$page_title = "Tests";
 
// include page header HTML
include_once "layout_head.php";
 
echo "<div class='col-md-12'>";
 
    // read all tests from the database
    $stmt = $test->readAll($from_record_num, $records_per_page);
 
    // count retrieved tests
    $num = $stmt->rowCount();
 
    // to identify page for paging
    $page_url="read_tests.php?";

    if(isset($_GET['tcode']) && isset($_GET['tname']) && isset($_GET['action']))
    {
        $tcode = $_GET['tcode'];
        $tname = $_GET['tname'];
        $action = $_GET['action'];

        if($action == "deleted")
        {
            echo "<div class='alert alert-success'>
                    Successfully deleted Test <b>{$tname}</b> ({$tcode}).
                </div>";
        }
        if($action == "error")
        {
            echo "<div class='alert alert-danger'>
                    Some erroe eccurred while deleting Test <b>{$tname}</b> ({$tcode}).<br />
                    Please ask super admin to manually delete files & database corresponding to this test.
                </div>";
        }
    }
 
    // include tests table HTML template
    include_once "read_tests_template.php";
 
echo "</div>";
 
// include page footer HTML
include_once "layout_foot.php";
?>
