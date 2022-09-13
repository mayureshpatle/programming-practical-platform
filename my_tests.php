
<?php
// core configuration
include_once "config/core.php";
 
// include login checker
$require_login=true;
include "login_checker.php";
 
// include classes
include_once 'config/database.php';
include_once 'objects/test.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$test = new Test($db);
 
// set page title
$page_title = "My Tests";
 
// include page header HTML
include_once "layout_head.php";
 
echo "<div class='col-md-12'>";

    if(isset($_GET['tcode']) && isset($_GET['action']))
    {
        if($_GET['action']=="deregistered") echo "<div class='alert alert-success'>You have successfully deregistered from {$_GET['tcode']}.</div>";
        if($_GET['action']=="deregister_failure") echo "<div class='alert alert-danger'>Some error occurred while deregistering you from {$_GET['tcode']}.</div>";
    }
 
    // read all tests from the database
    $stmt = $test->readMy($from_record_num, $records_per_page, $_SESSION['id']);
 
    // count retrieved tests
    $num = $stmt->rowCount();
 
    // to identify page for paging
    $page_url="my_tests.php?";
 
    // include tests table HTML template
    include_once "my_tests_template.php";
 
echo "</div>";
 
// include page footer HTML
include_once "layout_foot.php";
?>
