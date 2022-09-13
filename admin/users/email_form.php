<div class="alert alert-warning">
    <b>WARNING: Changing the Email ID will mark this user's account as unverified.</b><br />
    Verification link will be mailed to their new Email ID.<br />
    Kindly share thier Access Code with them for verification in case of any failue in sending the verification mail.
</div> 

</div>

<form action='change_email.php' method='post' class='col-md-12'>
<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>New Email ID</b></td>
            <td><input type='email' name='email' class='form-control' required value='<?php echo $email; ?>' /></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="col-md-2">
        <p><button type='submit' class='btn btn-primary btn-block'>
            <b><span class='glyphicon glyphicon-floppy-disk'></span> Save</b>
        </button></p>
    </div>
        
    <div class="col-md-2">
        <p><a href='details.php?id=<?php echo $user->id; ?>&action=cancelled' class='btn btn-danger btn-block'>
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>
