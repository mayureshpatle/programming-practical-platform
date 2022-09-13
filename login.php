<?php
    include_once "config/core.php";

    $page_title = "Login";
    $require_login=false;
    include_once "login_checker.php";
    $access_denied=false;

    //UserID Validation
    if($_POST)
    {
        include_once "config/database.php";
        include_once "objects/user.php";

        //get DB connection
        $database = new Database();
        $db = $database->getConnection();

        //object initialization
        $user = new User($db);

        //verify user ID & password in DB
        $user->id = $_POST['id'];
        $id_exists = $user->idExists();

        //user is not validated
        if($id_exists && (int)$user->status==0)
        {
            header("Location: {$home_url}invalid_user/?id={$user->id}");
        }

        // validate login
        else if ($id_exists && password_verify($_POST['password'],$user->password))
        {
        
            // if it is, set the session value to true
            $_SESSION['logged_in'] = true;
            $_SESSION['id'] = $user->id;
            $_SESSION['username']=$user->id;
            $_SESSION['type'] = $user->type;
            $_SESSION['name'] = htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') ;
            $_SESSION['email'] = $user->email;
            $_SESSION['status'] = $user->status;
            $_SESSION['mail_status'] = $user->mail_status;
        
            // if access level is 'Teacher', redirect to admin section
            if($user->type==1){
                header("Location: {$home_url}admin/index.php?action=login_success");
            }
        
            // else, redirect only to 'Student' section
            else{
                header("Location: {$home_url}index.php?action=login_success");
            }
        }
        
        // if user ID does not exist or password is wrong
        else
        {
            $access_denied=true;
        }
    }

    include_once "layout_head.php";

    echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";

        //alert
        // get 'action' value in url parameter to display corresponding prompt messages
        $action=isset($_GET['action']) ? $_GET['action'] : "";
        
        // tell the user he is not yet logged in
        if($action =='not_yet_logged_in'){
            echo "<div class='alert alert-danger margin-top-40' role='alert'>Please login.</div>";
        }

        else if($action == "password_reset")
        {
            echo "<div class='alert alert-success'> 
                    <strong>Your Password has been reset.</strong>
                </div>";
        }
        
        // tell the user to login
        else if($action=='please_login'){
            echo "<div class='alert alert-info'>
                <strong>Please login to access that page.</strong>
            </div>";
        }
        
        // tell the user if User ID is verified
        else if($action=='user_verified'){
            echo "<div class='alert alert-success'>
                <strong>Your account has been validated.</strong>
            </div>";
        }
        
        // tell the user if access denied
        if($access_denied){
            echo "<div class='alert alert-danger margin-top-40' role='alert'>
                Access Denied.<br /><br />
                Invalid User ID or Password.
            </div>";
        }
    
        // HTML login form
        echo "<div class='account-wall'>
                <div id='my-tab-content' class='tab-content'>
                    <div class='tab-pane active' id='login'>
                        <img class='profile-img' src='{$home_url}/images/logo.png'></img>
                        <form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                        <input type='text' name='id' class='form-control' placeholder='User ID' required autofocus style='margin-top:1em;' />
                        <input type='password' name='password' class='form-control' placeholder='Password' required style='margin-top:1em;' />
                        <input type='submit' class='btn btn-lg btn-primary btn-block' value='Log In' style='margin-top:1em;' />
                        <div class='margin-1em-zero text-align-center' style='margin-top:1em;'>
                            <a href='{$home_url}forgot_password'>Forgot password?</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>";

        echo "<div class='col-md-4'></div>";
    
    echo "</div>";
    
    // footer HTML and JavaScript codes
    include_once "layout_foot.php";
?>