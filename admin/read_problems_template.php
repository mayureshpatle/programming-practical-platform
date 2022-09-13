<?php
//create problem button
echo "<div class='alert alert-info'>";
echo "<a href='create_problem.php'>
        <b><span class='glyphicon glyphicon-plus'></span></b>
        <b> Create New Problem</b>
    </a>";
echo "</div>";

// display the table if the number of users retrieved was greater than zero
if($num>0){
 
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>Problem Code</th>";
        echo "<th>Name</th>";
        echo "<th>Created By</th>";
        echo "<th>Last Edited By</th>";
        echo "<th>Ready</th>";
        echo "<th>Edit</th>";
    echo "</tr>";
 
    // loop through the problem records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $problem_ready = $ready==1 ? "<span class='glyphicon glyphicon-ok text-success'></span>" : "<span class='glyphicon glyphicon-remove text-danger'></span>";
        $edit = "<span class='glyphicon glyphicon-pencil'></span>";

        $problem_link = "{$home_url}admin/problem/details.php?pcode={$pcode}";
 
        // display problem details
        echo "<tr>";
            echo "<td>{$pcode}</td>";
            echo "<td>{$pname}</td>";
            echo "<td>{$owner_name}</td>";
            echo "<td>{$editor_name}</td>";
            echo "<td>{$problem_ready}</td>";
            echo "<td><a href={$problem_link}>{$edit}</a></td>";
        echo "</tr>";
        }
 
    echo "</table>";
 
    $page_url="read_problems.php?";
    $total_rows = $problem->countAll();
 
    // actual paging buttons
    include_once 'paging.php';
}
 
// no problems available
else{
    echo "<div class='alert alert-danger'>
        <strong>No problems found.</strong>
    </div>";
}
?>