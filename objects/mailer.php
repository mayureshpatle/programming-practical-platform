<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\OAuth;
    use League\OAuth2\Client\Provider\Google;
    
    //include all installed mailing libraries
    require $mail_autoload;
        
    class Mailer
    {
        private $mailer_mail;
        private $oauth2_clientId;
        private $oauth2_clientSecret;
        private $oauth2_refreshToken;

        public function __construct($mailer_mail, $oauth2_clientId, $oauth2_clientSecret, $oauth2_refreshToken)
        {
            $this->mailer_mail = $mailer_mail;
            $this->oauth2_clientId = $oauth2_clientId;
            $this->oauth2_clientSecret = $oauth2_clientSecret;
            $this->oauth2_refreshToken = $oauth2_refreshToken;
        }


        //return exception on failure
        public function send_mail($to_email, $to_name, $subject, $body)
        {
            $mail = new PHPMailer(TRUE);
            try
            {
                //mail details
                $mail->setFrom($this->mailer_mail, 'A14Project');

                $mail->addAddress($to_email, $to_name);

                $mail->Subject = $subject;
                
                $mail->isHTML(true);

                $mail->Body = $body;

                //configuration
                $mail->isSMTP();
                $mail->Port = 587;
                $mail->SMTPAuth = TRUE;
                $mail->SMTPSecure = 'tls';

                //Google's SMTP
                $mail->Host = 'smtp.gmail.com';

                //XOAUTH2 AuthType
                $mail->AuthType = 'XOAUTH2';

                // Create a new OAuth2 provider instance.
                $provider = new Google(
                    [
                        'clientId' => $this->oauth2_clientId,
                        'clientSecret' => $this->oauth2_clientSecret,
                    ]
                );
                

                // Pass the OAuth provider instance to PHPMailer.
                $mail->setOAuth(
                    new OAuth(
                        [
                            'provider' => $provider,
                            'clientId' => $this->oauth2_clientId,
                            'clientSecret' => $this->oauth2_clientSecret,
                            'refreshToken' => $this->oauth2_refreshToken,
                            'userName' => $this->mailer_mail,
                        ]
                    )
                );

                $mail->send();

                return "Successful";
            }
            catch (Exception $e)
            {
                return $e->errorMessage();
            }
            catch (\Exception $e)
            {
                return $e->getMessage();
            }
        }
    }
?>