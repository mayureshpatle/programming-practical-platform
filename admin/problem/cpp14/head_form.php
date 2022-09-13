<div class='col-md-12'>
    <table class='table table-hover'>
        <tr>
            <td class='width-30-percent'><h3><b>Judge Key</h3></b></td>
            <td><h3><?php echo $problem->judge_key; ?></h3></td>
        </tr>
        <tr><td><td></td></td></tr>
    </table>
</div>

<form action='edit_head.php' method='post'>  <?php echo $pcode_ip;?>
<div class='col-md-12'>
    <div class='alert bg-info'>
        <h4><b>Head Code</b></h4>
        <samp>
        <textarea id='textarea' class='form-control' placeholder="Enter Head Code Here." rows=25 name='code' style='resize: none;' spellcheck='false' ><?php echo $code; ?></textarea>
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