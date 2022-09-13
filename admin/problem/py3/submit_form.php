<div class='col-md-12'>
    <div class='alert bg-info'>
        <h3><b><?php echo $problem->pname; ?></b></h3>
        <div class='well'> <?php echo md_format($description); ?> </div>
    </div>
</div>

<div class='col-md-12'>
    <table class='table'>
        <tr>
            <td class='width-30-percent'><b><h4>Time Limit</b></h4></td>
            <td><h4><?php echo $time_limit; ?> seconds</h4></td>
            <td></td><td></td><td></td>
        </tr>
        <tr><td></td><td></td><td></td><td></td><td></td></tr>
    </table>
</div>

<form action='submit.php' method='post'>  <?php echo $pcode_ip;?>
<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Your Solution</b></h4>
        <div class='well'>
            <?php echo md_format($locked_head); ?>
            <samp>
                <textarea id='textarea' class='form-control' placeholder="Enter Your Solution Here." rows=25 name='code' style='resize: none;' spellcheck='false' ><?php echo $code; ?></textarea>
            </samp>
            <?php echo md_format($locked_tail); ?>
        </div>
        <br />
    </div>
</div>

<div class="col-md-2">
    <p><button type='submit' class='btn btn-success btn-block' onclick="return confirm('<?php echo $submit; ?>')">
        <b><span class='glyphicon glyphicon-send'></span> Submit</b>
    </button></p>
</div>
</form> 

<form action='show.php' method='post'> <?php echo $pcode_ip;?>
<div class="col-md-2">
    <p><button class='btn btn-danger btn-block' type='submit'>
        <b><span class='glyphicon glyphicon-remove-sign'></span> Close</b>
    </button></p>
</div>
</form>

    <br />
    <br />
    <br />

<script> enableTab('textarea'); </script>