<?php 
$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-md-12'>";

echo "<div class='col-md-10'>";

if($action=="")
{
    echo "<div class='alert alert-info'>Test can be attempted only if it is <b>Live.</b></div>";
}
else if($action=='cancelled') 
{
    echo "<div class='alert alert-warning'> Action aborted. No changes were made.</div>";
}
else if($action=='changed') 
{
    echo "<div class='alert alert-success'> You've changed your roll number for this test.</div>";
}
else if($action=='change_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while changing your roll number.</div>";
}
else if($action=='updated')
{
    echo "<div class='alert alert-success'>Your name has been successfully updated according to your profile.</div>";
}
else if($action=='update_failed')
{
    echo "<div class='alert alert-danger'> Some error occured while updating your name.</div>";
}
else if($action=='not_live')
{
    echo "<div class='alert alert-danger'> This test is no longer accepting any responses. Contact your teacher if you think this is a mistake.</div>";
}
else if($action=='submitted') 
{
    echo "<div class='alert alert-success'> Test submitted successfully. You can still repoen and attempt the test till it is live.</div>";
}
else if($action=='no_lang')
{
    echo "<div class='alert alert-danger'> No Language Selected.</div>";
}
else if($action=='error')
{
    echo "<div class='alert alert-danger'> Some error occured. Please try again.</div>";
}

echo "</div>";

echo "<div class='col-md-2'>
        <p><a href='../my_tests.php' class='btn btn-danger btn-lg btn-block'>
            <b><span class='glyphicon glyphicon-log-out'></span> Exit </b>
        </a></p>
    </div>
</div>";

?>