<?php include_once "../libs/js/utils.php"; ?>
<form action='create_problem.php' method='post' id='register'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>Problem Code</td>
            <td><input type='text' name='pcode' class='form-control' placeholder="Ex: 3DS_SORT, ADS_TRIE" required value="<?php echo isset($_POST['pcode']) ? htmlspecialchars($_POST['pcode'], ENT_QUOTES) : "";  ?>" onkeypress="return keyInput(event)" /></td>
        </tr>

        <tr>
            <td>Problem Name</td>
            <td><input type='text' name='pname' class='form-control' placeholder="Ex: Merge Sort, Skip List" required value="<?php echo isset($_POST['pname']) ? htmlspecialchars($_POST['pname'], ENT_QUOTES) : "";  ?>" /></td>
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