<form action='delete.php' method='post' class='col-md-12'>
<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
    <table class='table table-responsive'>
        <tr>
            <td class="col-md-3"><b>Enter Your Password</b></td>
            <td><input type='password' name='password' class='form-control' required /></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="col-md-3">
        <p><button type='submit' class='btn btn-danger btn-block btn-lg'>
            <b><span class='glyphicon glyphicon-trash'></span> Confirm Delete</b>
        </button></p>
    </div>
        
    <div class="col-md-3">
        <p><a href='details.php?id=<?php echo $user->id; ?>&action=cancelled' class='btn btn-danger btn-block btn-lg'>
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>

</div>
</form>