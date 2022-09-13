<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Forgot Password";
 
// include page header HTML
include_once "layout_head.php";
?>

<div class="col-md-12">
    <div class="alert alert-warning">
        NOTE: If you already have your access code then use the reset password with access code option.
        Kindly note that requesting password reset link will regenerate the access code, and the previous access code will no longer work.
    </div>

    <table class="table"> <tr><td></td></tr></table>

    <div class="alert alert-info">
        Select one of the following options to reset your password.
    </div>

    <p>
        <a href="<?php echo $home_url?>forgot_pass_access/" class="btn btn-primary btn-lg btn-block text-left" type="submit">
            <b>Use Access Code to Reset Password</span></b>
        </a>
    </p>

    <p>
        <a href="<?php echo $home_url?>forgot_pass_mail/" class="btn btn-primary btn-lg btn-block" type="submit">
            <b>Request Password Reset Link</span></b>
        </a>
    </p>

    <br />
    <table class="table"> <tr><td></td></tr></table>


</div>

<?php 
include_once "layout_foot.php";
?>