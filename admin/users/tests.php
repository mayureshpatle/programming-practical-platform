
<?php
// core configuration
include_once "../../config/core.php";
 
// include login checker
include "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/test.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$test = new Test($db);
 
// set page title
$page_title = "Registered Tests (User: {$_GET['id']})";
 
// include page header HTML
include_once "../layout_head.php";
 
echo "<div class='col-md-12'>";
 
    // read all tests from the database
    $stmt = $test->readMy($from_record_num, $records_per_page, $_GET['id']);
 
    // count retrieved tests
    $num = $stmt->rowCount();
 
    // to identify page for paging
    $page_url="tests.php?";
 
    // include tests table HTML template
    include_once "tests_template.php";
 
echo "</div>";
 
// include page footer HTML
include_once "../layout_foot.php";
?>
