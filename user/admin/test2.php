<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer autoloader
require '..\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require '../vendor\phpmailer\phpmailer\src\SMTP.php';
require '..\vendor/phpmailer/PHPMailer/src/Exception.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'kevin.gangoso@gmail.com';                     // SMTP username
    $mail->Password   = 'eqxmmmmfosdsrxny';                               // SMTP password gmail app password
    $mail->SMTPSecure = 'tls';            // Enable implicit TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('kevin.gangoso@gmail.com', 'Kevin');
    $mail->addAddress('kevin.gangoso@gmail.com', 'Grocer Ease');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
