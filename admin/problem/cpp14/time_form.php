
<form action='time.php' method='post'>  <?php echo $pcode_ip;?>
<div class='col-md-12'>
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Time Limit</b></td>
            <td><input type='number' name='cpp14_time' class='form-control' min='0.01' step='0.01' required value='<?php echo $problem->cpp14_time; ?>' /></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>

<div class="col-md-2">
    <p><button type='submit' class='btn btn-primary btn-block' onclick="return confirm('The status for this language will be set to `Not Ready` after this action.')">
        <b><span class='glyphicon glyphicon-floppy-disk'></span> Save</b>
    </button></p>
</div>
</form>
        
<div class="col-md-2">
    <form method='post' action='show.php?action=cancelled'> <?php echo $pcode_ip; ?>
    <p><button class='btn btn-danger btn-block' onclick="return confirm('Do you really want to abort changing the time limit for this language?')" type='submit'>
        <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
    </button></p>
    </form>
</div>
</form>
