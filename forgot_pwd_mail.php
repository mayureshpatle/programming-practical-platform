<?php
    $send_to_email=$user->email;
    $send_to_name=$user->name;
    $body="Hi {$send_to_name}.<br /> <br />";
    $body.="Use the following link to reset you password on the Programming Practial and Examination Platform:<br />";
    $body.="{$home_url}reset_password/?id={$user->id}&access_code={$access_code} <br />";
    $body.="Your <b>User Id</b> is <b>{$user->id}<br /></b>";
    $subject="Password Reset Link";
    $mailer = new Mailer($mailer_mail, $oauth2_clientId, $oauth2_clientSecret, $oauth2_refreshToken);
    $mail_status = $mailer->send_mail($send_to_email, $send_to_name, $subject, $body);
?>
