<?php
// display the table if the number of users retrieved was greater than zero
if($num>0){
 
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>Registration No.</th>";
        echo "<th>Roll No.</th>";
        echo "<th>Name</th>";
        echo "<th>Score</th>";
        echo "<th>Submitted</th>";
        echo "<th>Submission</th>";
    echo "</tr>";
 
    // loop through the problem records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $link = "../../tests/{$test->tcode}/{$user_id}/best.txt";

        $submitted = $submitted ? "<span class='glyphicon glyphicon-ok text-success'></span>" : "<span class='glyphicon glyphicon-remove text-danger'></span>";
 
        // display problem details
        echo "<tr><form method='post' target='_blank' action='open_solution.php'>";
        echo "<input name='tcode' type='hidden' value='{$test->tcode}' />";
        echo "<input name='reg_no' type='hidden' value='{$user_id}' />";
            echo "<td>{$user_id}</td>";
            echo "<td>{$roll_no}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$score}</td>";
            echo "<td>{$submitted}</td>";
            echo "<td><button type='submit' class='btn btn-link'><span class='glyphicon glyphicon-new-window'></span></button></td>";
        echo "</form></tr>";

        }
 
    echo "</table>";
 
    $page_url="read_result.php?tcode={$test->tcode}&";
    $total_rows = $result->countAll();
 
    // actual paging buttons
    include_once '../paging.php';
}
 
// no problems available
else{
    echo "<div class='alert alert-danger'>";
        echo "<b>No student has registerd for {$test->tname} </b>(<b>{$test->tcode}</b>)<b>.</b>";
    echo "</div>";
}
?>