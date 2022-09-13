<?php include_once "../libs/js/utils.php"; ?>
<form action='create_test.php' method='post' id='register'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>Test Code</td>
            <td><input type='text' name='tcode' class='form-control' placeholder="Ex: 3CT_DS_1, 4CT_ADS_8, 6_DAA_ESE" required value="<?php echo isset($_POST['tcode']) ? htmlspecialchars($_POST['tcode'], ENT_QUOTES) : ""; ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>Test Name</td>
            <td><input type='text' name='tname' class='form-control' placeholder="Ex: Practical No 1, 7CT End Semester Exam" required value="<?php echo isset($_POST['tname']) ? htmlspecialchars($_POST['tname'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td>Problem Code</td>
            <td><input type='text' name='pcode' class='form-control' placeholder="Problem Code of EXISTING problem" required value="<?php echo isset($_POST['pcode']) ? htmlspecialchars($_POST['pcode'], ENT_QUOTES) : ""; ?>" onkeypress="return keyInput(event)" /></td>
        </tr>
 
        <tr>
            <td>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Create
                </button>
            </td>
            <td></td>
        </tr>
 
    </table>
</form>