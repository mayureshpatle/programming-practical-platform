<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen" />
 
    <!-- admin custom CSS -->
    <link href="<?php echo $home_url . "libs/css/user.css" ?>" rel="stylesheet" />
 
</head>
<body>

<div class="container-fluid">

<br />

<?php
$best = $fetch_error ? "" : $test->bestScore();
$score_status = "<span class='glyphicon glyphicon-" . ($best > 0 ? "ok" : "remove") . "'></span>";
$score_message = $fetch_error ? "Unable to fetch your best recorded score." :"Your best recorded score is <b>{$best}</b> {$score_status}";
if($best == 100) $score_message =  "<big>🎊</big> {$score_message} <big>🎊</big>";
$score_message = "<h4>{$score_message}</h4>";
$score_alert = !$fetch_error && $best>0 ? ($best == 100 ? "'alert alert-success'" : "'alert alert-warning'") : "'alert alert-danger'";
echo "<div class={$score_alert}>
        {$score_message}
    </div>";
?>