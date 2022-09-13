<?php 
$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-md-12'>";

echo "<div class='col-md-10'>";

if($action=="")
{
    echo "<div class='alert alert-info'>NOTE: Before starting the test, ensure that the used Problem is Ready.</div>";
}
else if($action=='cancelled') 
{
    echo "<div class='alert alert-warning'> Action aborted. No changes were made.</div>";
}
else if($action=='renamed') 
{
    echo "<div class='alert alert-success'> Test {$test->tcode} Renamed Successfully.</div>";
}
else if($action=='rename_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while renaming test {$test->tcode}.</div>";
}
else if($action=='regenerated')
{
    echo "<div class='alert alert-success'>Registration Key has been regenerated. Previous Registration Key is now Invalid.<br /></div>";
}
else if($action=='regeneration_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while regenerating the Registration Key.</div>";
}
else if($action=='error')
{
    echo "<div class='alert alert-danger'> Some error occured. Please try again.</div>";
}
else if($action=='not_ready')
{
    echo "<div class='alert alert-danger'> Cannot start this test because the included problem is not ready. Kindly make the problem ready first then try again.</div>";
}
else if($action=='started')
{
    echo "<div class='alert alert-success'>{$test->tname} is now <b>LIVE</b>. You can stop the test whenever you want.</div>";
}
else if($action=='stopped')
{
    echo "<div class='alert alert-success'>{$test->tname} is now <b>CLOSED</b>. No further submissions will be considered. You can resume (start) the test if required.</div>";
}
else if($action=='changed')
{
    echo "<div class='alert alert-success'>Problem has been changed. All previous solutions are now invalidated.</div>";
}
else if($action=='change_failed')
{
    echo "<div class='alert alert-danger'>Some error occurred while changing the problem. Please try again or Delete this test and create a new Test for that problem.</div>";
}

echo "</div>";

echo "<div class='col-md-2'>
        <p><a href='../read_tests.php' class='btn btn-danger btn-lg btn-block'>
            <b><span class='glyphicon glyphicon-log-out'></span> Exit </b>
        </a></p>
    </div>
</div>";

?>