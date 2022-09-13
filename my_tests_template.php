<?php

// display the table if the number of users retrieved was greater than zero
if($num>0){

    echo "<div class='alert alert-info'>";
        echo "<a href='test_registration.php'>
                <span class='glyphicon glyphicon-plus'></span>
                <b> Register For Test</b>
            </a>";
    echo "</div>";
 
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>Code</th>";
        echo "<th>Name</th>";
        echo "<th>Status</th>";
        echo "<th>Open</th>";
    echo "</tr>";
 
    // loop through the test records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        
        $link = $home_url . "test/details.php?tcode={$tcode}";

        $status = $status==1 ? "<span class='label label-success'>Live</span>" : "<span class='label label-danger'>Closed</span>";
 
        // display test details
        echo "<tr>";
            echo "<td>{$tcode}</td>";
            echo "<td>{$tname}</td>";
            echo "<td><big>{$status}</big></td>";
            echo "<td><a href={$link}><big><b><span class='glyphicon glyphicon-circle-arrow-right'></span></b></big></a></td>";
        echo "</tr>";
        }
 
    echo "</table>";
 
    $page_url="my_tests.php?";
    $total_rows = $test->countMY($_SESSION['id']);
 
    // actual paging buttons
    include_once 'paging.php';
}
 
// no tests available
else{
    echo "<div class='alert alert-danger'>
        <strong>No tests found.</strong>
    </div>";

    echo "<div class='alert alert-info'>";
        echo "<a href='test_registration.php'>
                <span class='glyphicon glyphicon-plus'></span>
                <b> Register For Test</b>
            </a>";
    echo "</div>";
}
?>