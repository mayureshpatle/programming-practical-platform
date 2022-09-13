<?php
// display the table if the number of users retrieved was greater than zero
if($num>0){
 
    echo "<table class='table table-hover table-responsive table-bordered'>";
 
    // table headers
    echo "<tr>";
        echo "<th>User ID</th>";
        echo "<th>Name</th>";
        echo "<th>Email</th>";
        echo "<th>Type</th>";
        echo "<th>Verified</th>";
        echo "<th>Details</th>";
    echo "</tr>";
 
    // loop through the user records
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $user_type = $type==1 ? "Teacher" : "Student";
        $verified = $status==1 ? "<span class='glyphicon glyphicon-ok text-success'></span>" : "<span class='glyphicon glyphicon-remove text-danger'></span>";
        $edit_link = "users/details.php?id={$user_id}";
        $edit = "<span class='glyphicon glyphicon-export'></span>";

        if($user_id==$_SESSION['id']) $name .= " (You)";
        
 
        // display user details
        echo "<tr>";
            echo "<td>{$user_id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$user_type}</td>";
            echo "<td>{$verified}</td>";
            echo "<td><a href='{$edit_link}'>{$edit}</a></td>";
        echo "</tr>";
        }
 
    echo "</table>";
 
    $page_url="read_users.php?";
    $total_rows = $user->countAll();
 
    // actual paging buttons
    include_once 'paging.php';
}
 
// no users found
else{
    echo "<div class='alert alert-danger'>
        <strong>No users found.</strong>
    </div>";
}
?>