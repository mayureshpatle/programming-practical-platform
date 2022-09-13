<?php 
$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-md-12'>";

echo "<div class='col-md-10'>";

if($action=="")
{
    echo "<div class='alert alert-info'>If status is 'Not Ready' then submit a 100% accurate solution in this language to make it 'Ready'.</div>";
}
else if($action=="judge_key")
{
    echo "<div class='alert alert-info'>Output should only contain lines in format 'Judge_Key:score' (without quotes).<br />
            Total score will be sum of scores printed in each line. Ensure that this sum should be 100 for accurate solutions. <br />
            If this format is violated or the sum is greater than 100 then submission will get a score of 0. <br />
        </div>";
}
else if($action=='cancelled') 
{
    echo "<div class='alert alert-warning'> Action aborted. No changes were made.</div>";
}

echo "</div>";

echo "<div class='col-md-2'>
        <p><a href='../details.php?pcode={$problem->pcode}' class='btn btn-danger btn-lg btn-block'>
            <b><span class='glyphicon glyphicon-log-out'></span> Close</b>
        </a></p>
    </div>
</div>";

?>