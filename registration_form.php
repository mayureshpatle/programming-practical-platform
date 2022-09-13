<?php include_once "libs/js/utils.php"; ?>
<form action='register.php' method='post' id='register'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>Registration Number</td>
            <td><input type='text' name='user_id' class='form-control' placeholder="College Registration Number, Ex: 17010471" required value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id'], ENT_QUOTES) : "";  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>Name</td>
            <td><input type='text' name='name' class='form-control' placeholder="Full Name, Ex: MAYURESH PATLE" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : '';  ?>" /></td>
        </tr>

        <tr>
            <td>Password</td>
            <td><input type='password' name='password' class='form-control' placeholder="Password" required id='passwordInput' minLength=8 maxLength=13></td>
        </tr>

        <tr>
            <td>Confirm Password</td>
            <td><input type='password' name='confirm_password' class='form-control' placeholder="Confirm Password" required id='confirmPasswordInput' minLength=8 maxLength=13></td>
        </tr>
 
        <tr>
            <td>
                <button type="submit" class="btn btn-lg btn-primary">
                    <b><span class="glyphicon glyphicon-plus"></span> Register</b>
                </button>
            </td>
            <td>
                <div class="alert alert-info">
                    NOTE: Passowrd should be 8 to 13 characters long.
                </div>
            </td>
        </tr>
 
    </table>
</form>