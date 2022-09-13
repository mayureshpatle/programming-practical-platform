<?php 
$action = isset($action) ? $action : ( isset($_GET['action']) ? $_GET['action'] : "" );

echo "<div class='col-md-12'>";

echo "<div class='col-md-10'>";

if($action=="")
{
    echo "<div class='alert alert-info'>Save Code button will only save the code, to run and generate score, you need to submit it.</div>";
}
else if($action=='error')
{
    echo "<div class='alert alert-danger'> Some error occured. Please try again.</div>";
}

echo "</div>";

$end_link = "details.php?tcode={$_POST['tcode']}&action=submitted";
$end_warn = "Make sure you have submitted the code first. You will lose the typed code if you end this test without submitting.";
?>

<div class='col-md-2'>
<form method='post' action="<?php echo $end_link; ?>">
    <p><button type="submit" class='btn btn-danger btn-lg btn-block' onclick="return confirm('<?php echo $end_warn;?>')">
        <b><span class='glyphicon glyphicon-log-out'></span> End Test</b>
    </button></p>
</form>
</div>
</div>