<div class='col-md-12'>
    <table class='table table-hover'>

        <form method="post" action="edit_roll.php"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Your Roll No.</b></td>
            <td class='col-md-8'><?php echo $result->roll_no; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $edit_roll_confirm; ?>')" ><b>Change</b></button></td>
        </tr>
        </form>

        <form method="post" action="update_name.php"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-2'><b>Your Name</b></td>
            <td class='col-md-8'><?php echo $result->name; ?></td>
            <td><button type="submit" class='btn btn-primary btn-block' onclick="return confirm('<?php echo $update_name_confirm; ?>')"><b>Update</b></button></td>
        </tr>
        </form>

        <tr>
            <td class='col-md-2'><b>Best Recorded Score</b></td>
            <td class='col-md-8'><b><?php echo $result->score; ?></b></td>
            <td><a href='#' class='btn btn-sm btn-default btn-block' disabled='disabled'><b><?php echo $submitted_on; ?></b></div></td>
        </tr>

        <form method="post" action="<?php echo $test_link;?>"> <?php echo $tcode_ip;?>
        <tr>
            <td class='col-md-2'><h3><b>Test Status</b></h3></td>
            <td class='col-md-8'><h3><b><?php echo $status; ?></b></h3></td>
            <td><h3><button type="submit" class="btn btn-primary btn-lg btn-block" <?php echo $disable; ?>><b>Attempt</b></h3></td>
        </tr>
        </form>

    </table>

    <form method="post" action="deregister.php"> <?php echo $tcode_ip;?>
    <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="return confirm('<?php echo $delete_warn; ?>')"><b><?php echo $delete_text?></button>
    </form>
    <br /><br />
</div>