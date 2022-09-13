<?php
//create test button
echo "<div class='alert alert-info'>";
echo "<a href='create_test.php'>
        <b><span class='glyphicon glyphicon-plus'></span></b>
        <b> Create New Test</b>
    </a>";
echo "</div>";

// display the table if the number of users retrieved was greater than zero
if($num>0){
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>Test Code</th>";
        echo "<th>Test Name</th>";
        echo "<th>Problem Code</th>";
        echo "<th>Status</th>";
        echo "<th>Registration Key</th>";
        echo "<th>Details</th>";
        echo "<th>Result</th>";
    echo "</tr>";
 
    // loop through the test records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $test_status = $status==1 ? "<span class='label label-success'>Live</span>" : "<span class='label label-danger'>Closed</span>";

        $details = "<span class='glyphicon glyphicon-link'></span>";
        $detail_link = "{$home_url}admin/test/details.php?tcode={$tcode}";
        
        $result_link = "{$home_url}admin/test/read_result.php?tcode={$tcode}";
 
        // display test details
        echo "<tr>";
            echo "<td>{$tcode}</td>";
            echo "<td>{$tname}</td>";
            echo "<td>{$pcode}</td>";
            echo "<td><big>{$test_status}</big></td>";
            echo "<td>{$reg_key}</td>";
            echo "<td><a href={$detail_link}>{$details}</td>";
            echo "<td><a href={$result_link} target='_blank'><span class='glyphicon glyphicon-new-window'></span></a></td>";
        echo "</tr>";
        }
 
    echo "</table>";
 
    $page_url="read_tests.php?";
    $total_rows = $test->countAll();
 
    // actual paging buttons
    include_once 'paging.php';
}
 
// no tests available
else{
    echo "<div class='alert alert-danger'>
        <strong>No tests found.</strong>
    </div>";
}
?>