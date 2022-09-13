<form action='edit_locked_tail.php' method='post'>  <?php echo $pcode_ip;?>
<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Locked Tail Code</b><i>(final brackets of function, class or struct to display, visible to student)</i></h4>
        <samp>
        <textarea id='textarea' class='form-control' placeholder="Enter Locked Tail Code Here." rows=25 name='code' style='resize: none;' spellcheck='false' ><?php echo $code; ?></textarea>
        </samp>
        <br />
    </div>
</div>

<div class="col-md-2">
    <p><button type='submit' class='btn btn-primary btn-block' onclick="return confirm('<?php echo $save; ?>')">
        <b><span class='glyphicon glyphicon-floppy-disk'></span> Save</b>
    </button></p>
</div>
</form>

<form action='show.php?action=cancelled' method='post'> <?php echo $pcode_ip;?>
<div class="col-md-2">
    <p><button class='btn btn-danger btn-block' onclick="return confirm('<?php echo $abort; ?>')" type='submit'>
        <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
    </button></p>
</div>
</form>

    <br />
    <br />
    <br />

</form>

<script> enableTab('textarea'); </script>