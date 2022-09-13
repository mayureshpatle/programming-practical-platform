
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
 
 <table class='table table-responsive'>

     <tr>
         <td class="col-md-3">Old Password</td>
         <td><input type='password' name='old_password' class='form-control' placeholder="Enter Your Current Password Here" required id='passwordInput' minLength=8 maxLength=13></td>
     </tr>

     <tr>
         <td>New Password</td>
         <td><input type='password' name='new_password' class='form-control' placeholder="New Password" required id='passwordInput' minLength=8 maxLength=13></td>
     </tr>

     <tr>
         <td>Confirm New Password</td>
         <td><input type='password' name='confirm_password' class='form-control' placeholder="Confirm New Password" required id='confirmPasswordInput' minLength=8 maxLength=13></td>
     </tr>

     <tr>
         <td></td>
         <td></td>
     </tr>

 </table>

 <div class="col-md-3">
     <p><button type="submit" class="btn btn-lg btn-block btn-primary">
         <b><span class="glyphicon glyphicon-refresh"></span> Reset Password</b>
     </button></p>
 </div>
     
 <div class="col-md-3">
     <p><a href='details.php?action=cancelled' class='btn btn-danger btn-block btn-lg'>
         <b><span class='glyphicon glyphicon-remove-sign'></span> Cancel</b>
     </a></p>
 </div>
</form>

</div>
