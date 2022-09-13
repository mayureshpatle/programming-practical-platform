<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/user.php';

$go_ahead = false;
// get database connection
$database = new Database();
$db = $database->getConnection();

if(isset($_POST['id']))
{
    try
    {
        $user = new User($db);
        $user->id = $_POST['id'];
        if($user->idExists())
        {
            if(isset($_POST['type']))
            {
                $type = $_POST['type'];
                if($type == $user->type) header("Location: details.php?id=$user->id&action=cancelled");
                $go_ahead = true;
            }
        }
    }
    catch(Exception $e)
    {
        //echo "damnnn...";
    }
}

if(!$go_ahead) header("Location: {$home_url}");

$user->type = $type;
$action = $user->changeType() ? "toggled" : "toggle_failed";

header("Location: details.php?id=$user->id&action=$action");
?>
