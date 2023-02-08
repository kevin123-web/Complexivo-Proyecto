<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

class Mailer
{
    function sendEmail($email, $asunto, $cuerpo)
    {
        require './config/config.php';
        require './phpmailer/src/PHPMailer.php';
        require './phpmailer/src/SMTP.php';
        require './phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;          
            $mail->isSMTP();
            $mail->Host       = MAILS_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAILS_USER;
            $mail->Password   = MAILS_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAILS_PORT;                         

            //Recipients
            $mail->setFrom(MAILS_USER, 'TIENDA SUBLIMADOS');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;
            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

            if($mail->send()){
                return true;
            }else{
                return false;
            }

        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje : {$mail->ErrorInfo}";
            return false ;
        }
    }
}
