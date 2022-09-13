<?php
    $send_to_email=$user->email;
    $send_to_name=$user->name;
    $body="Hi {$send_to_name}.<br /><br />";
    $body.="This mail is regarding your registration on the Programming Practial and Examination Platform.<br />";
    $body.="<br />Click the following link to verify your account on: <br/>";
    $body.="{$home_url}verify/?id={$user->id}&access_code={$user->access_code}";
    $subject="Action Required: Complete Your Registration / Verification";
    $mailer = new Mailer($mailer_mail, $oauth2_clientId, $oauth2_clientSecret, $oauth2_refreshToken);
    $mail_status = $mailer->send_mail($send_to_email, $send_to_name, $subject, $body);
?>