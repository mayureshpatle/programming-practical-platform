<div class='col-md-12'>
<form method="post" action="select_language.php" class='well well-sm'>
    <?php echo $tcode_ip; ?>
    <p><h4><b>Selected Language:</b> <b class='text-muted'><?php echo $test->lang; ?></b></p>
    <p><button type="submit" class="btn btn-lg btn-primary" onclick="return confirm('<?php echo $lang_warn; ?>')">
        <b><span class='glyphicon glyphicon-retweet'></span> Change</b>
    </button></p>
</form>
</div>

<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Problem Description</b></h4>
        <div class='well'> <?php echo $description; ?> </div>
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

<form action='submit.php' method='post' target="status_frame">
<?php echo $tcode_ip;?>
<?php echo $lang_ip;?>
<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Your Solution</b></h4>
        <div class='well'>
            <?php echo $locked_head; ?>
            <samp>
                <textarea id='textarea' class='form-control' placeholder="Enter Your Solution Here." rows=25 name='code' style='resize: none;' spellcheck='false' required><?php echo $code; ?></textarea>
            </samp>
            <?php echo $locked_tail; ?>
        </div>
        <br />
    </div>
</div>

<div class="col-md-2">
    <p><button type='submit' class='btn btn-success btn-lg btn-block' onclick="return confirm('<?php echo $submit; ?>')">
        <b><span class='glyphicon glyphicon-send'></span> Submit</b>
    </button></p>
</div>

<div class="col-md-2">
    <p><button type='submit' class='btn btn-primary btn-lg btn-block' formtarget="status_frame" formaction="save.php">
        <b><span class='glyphicon glyphicon-floppy-disk'></span> Save Code</b>
    </button></p>
</div>
</form>

<script> enableTab('textarea'); </script>

<br />
<br />

<div class="col-md-12">

<table class="table"><tr><th></th></tr></table>

<div class="alert bg-success">
<h4><b>Submission Status</b> 
<a href="new_frame.php?tcode=<?php echo $test->tcode; ?>" target="status_frame" class="btn btn-sm btn-default"><b><span class='glyphicon glyphicon-erase'></span> Reset</b></a></h4>
<iframe name="status_frame" style="height:500px;width:100%;border: 1px solid green;background-color: white" src="new_frame.php?tcode=<?php echo $test->tcode?>"></iframe>
</div>
</div>

<br />
<br />