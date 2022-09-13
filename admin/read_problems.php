<?php
// core configuration
include_once "../config/core.php";
 
// check if logged in as admin
include_once "login_checker.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/problem.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$problem = new Problem($db);
 
// set page title
$page_title = "Problems";
 
// include page header HTML
include_once "layout_head.php";
 
echo "<div class='col-md-12'>";
 
    // read all problems from the database
    $stmt = $problem->readAll($from_record_num, $records_per_page);
 
    // count retrieved problems
    $num = $stmt->rowCount();
 
    // to identify page for paging
    $page_url="read_problems.php?";
 
    // include problems table HTML template
    include_once "read_problems_template.php";
 
echo "</div>";
 
// include page footer HTML
include_once "layout_foot.php";
?>
