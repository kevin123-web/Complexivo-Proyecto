<?php

use PHPMailer\PHPMailer\{PHPMailer ,SMTP, Exception};


require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;           //SMTP::DEBUG_OFF
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;            
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = MAIL_USER;                     
    $mail->Password   = MAIL_PASS;                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
    $mail->Port       = MAIL_PORT;                         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(MAIL_USER, 'TIENDA SUBLIMADOS');
    $mail->addAddress('kjr.motoche@yavirac.edu.ec', 'Joe User');     

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Detalles de su compra';
    $cuerpo = '<h4>Gracias por su compra!!</h4>';
    $cuerpo .= '<p>El ID de su compra es <br>'. $id_transaction .'</br> </p>';
    $mail->Body    = $cuerpo;
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');
    $mail->send();

} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra : {$mail->ErrorInfo}";
    exit;
}