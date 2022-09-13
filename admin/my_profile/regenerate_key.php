<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";
 
// include classes
include_once '../../config/database.php';
include_once '../../objects/user.php';
include_once '../../libs/php/utils.php';

$go_ahead = false;

// get database connection
$database = new Database();
$db = $database->getConnection();

try
{
    $user = new User($db);
    $user->id = $_SESSION['id'];
    if($user->idExists())
    {
        $go_ahead = true;
    }
}
catch(Exception $e)
{
    //echo "damnnn...";
}

if(!$go_ahead) header("Location: {$home_url}");

$utils = new Utils();
$user->access_code = $utils->getToken(32);
$action = $user->updateAccessCode() ? "regenerated" : "regeneration_failed";
header("Location: details.php?action={$action}");

?>