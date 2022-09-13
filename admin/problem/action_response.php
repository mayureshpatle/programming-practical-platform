<?php 
$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-md-12'>";

echo "<div class='col-md-10'>";

if($action=="")
{
    echo "<div class='alert alert-info'>Ensure that the status of all Enabled Languages is Ready, and atleast one language is Enabled.</div>";
}
else if($action=='cancelled') 
{
    echo "<div class='alert alert-warning'> Action aborted. No changes were made.</div>";
}
else if($action=='renamed') 
{
    echo "<div class='alert alert-success'> Problem {$problem->pcode} Renamed Successfully.</div>";
}
else if($action=='rename_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while renaming problem {$problem->pcode}.</div>";
}
else if($action=='regenerated')
{
    echo "<div class='alert alert-success'>Judge Key has been regenerated.<br />Kindly, update the new Judge Key in all language divers.</div>";
}
else if($action=='regeneration_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while regenerating the Judge Key.</div>";
}
else if($action=='desc_updated')
{
    echo "<div class='alert alert-success'> Problem description has been updated.</div>";
}

echo "</div>";

echo "<div class='col-md-2'>
        <p><a href='../read_problems.php' class='btn btn-danger btn-lg btn-block'>
            <b><span class='glyphicon glyphicon-log-out'></span> Close</b>
        </a></p>
    </div>
</div>";

?>