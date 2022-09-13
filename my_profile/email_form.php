<div class="alert alert-warning">
    <b>WARNING: Changing your Email ID will mark your account as unverified, and you will be logged out from the current session.</b><br />
    Verification link will be mailed to your new Email ID.<br />
    It is suggested that you should copy and save your access code before changing your Emai ID.<br />
    You can use your Access Code for verification in case of any failue in sending the verification mail.
</div> 

</div>

<form action='change_email.php' method='post' class='col-md-12'>
    <table class='table table-responsive'>
        <tr>
            <td class='width-30-percent'><b>Email ID</b></td>
            <td><input type='email' name='email' class='form-control' required value='<?php echo $email; ?>' /></td>
        </tr>

        <tr>
            <td><b>Your Password</b></td>
            <td><input type='password' name='password' class='form-control' required /></td>
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
        <p><a href='details.php?action=cancelled' class='btn btn-danger btn-block'>
            <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
        </a></p>
    </div>
</form>
