<?php include_once "libs/js/utils.php"; ?>
<form action='test_registration.php' method='post' id='test_register'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-70-percent'>Test Code</td>
            <td><input type='text' name='tcode' class='form-control' placeholder="Test Code (Provided by Teacher)" required value="<?php echo isset($_POST['tcode']) ? htmlspecialchars($_POST['tcode'], ENT_QUOTES) : "";  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>Roll Number</td>
            <td><input type='number' name='roll_no' class='form-control' min=1 placeholder="Exam/Class Roll Number" required value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td>Test Registration Key</td>
            <td><input type='text' name='reg_key' class='form-control' placeholder="Test Registration Key (Provided by Teacher)" required value="<?php echo isset($_POST['reg_key']) ? htmlspecialchars($_POST['reg_key'], ENT_QUOTES) : "";  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Register
                </button>
            </td>
            <td></td>
        </tr>
 
    </table>
</form>