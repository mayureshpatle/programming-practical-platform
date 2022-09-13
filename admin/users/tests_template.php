<?php

// display the table if the number of users retrieved was greater than zero
if($num>0){
 
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>Code</th>";
        echo "<th>Name</th>";
        echo "<th>Status</th>";
        echo "<th>Details</th>";
        echo "<th>Result</th>";
    echo "</tr>";
 
    // loop through the test records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $details = "<span class='glyphicon glyphicon-link'></span>";
        $detail_link = "{$home_url}admin/test/details.php?tcode={$tcode}";
        
        $result_link = "{$home_url}admin/test/read_result.php?tcode={$tcode}";

        $status = $status==1 ? "<span class='label label-success'>Live</span>" : "<span class='label label-danger'>Closed</span>";
 
        // display test details
        echo "<tr>";
            echo "<td>{$tcode}</td>";
            echo "<td>{$tname}</td>";
            echo "<td><big>{$status}</big></td>";
            echo "<td><a href={$detail_link}>{$details}</td>";
            echo "<td><a href={$result_link} target='_blank'><span class='glyphicon glyphicon-new-window'></span></a></td>";
        echo "</tr>";
        }
 
    echo "</table>";
 
    $page_url="my_tests.php?";
    $total_rows = $test->countMY($_GET['id']);
 
    // actual paging buttons
    include_once '../paging.php';
}
 
// no tests available
else{
    echo "<div class='alert alert-danger'>
        <strong>No tests found.</strong>
    </div>";
}
?>