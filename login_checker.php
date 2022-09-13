<?php
    //Admin (Teacher)
    if(isset($_SESSION['type']) && ($_SESSION['type']=="1" || $_SESSION['type']==1))
    {
        header("Location: {$home_url}admin/index.php?action=logged_in_as_admin");
    }

    //already logged in & trying to login or register
    else if(isset($page_title) && ($page_title=="Login" || $page_title=="User Registration" || $page_title=="Forgot Password" || $page_title=="Reset Password" || $page_title=="Verify"))
    {
        if(isset($_SESSION['type']) && ($_SESSION['type']=="0" || $_SESSION['type']==0))
        {
            header("Location: {$home_url}index.php?action=already_logged_in");
        }
    }

    //require_login is true
    else if(isset($require_login) && $require_login==true)
    {
        if(!isset($_SESSION['id']))
        {
            header("Location: {$home_url}login.php?action=please_login");
        }
    }

    else
    {
        //user can stay here
    }
?>