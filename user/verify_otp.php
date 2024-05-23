<?php
require 'config.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/autoload.php'; // Include the Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP

    $to = $email;
    $from = "kevin.gangoso@gmail.com";
    $fromName = "Grocer East Store";
    $subject = "OTP Authentication";
    $message = "Your OTP Code is: $otp"; // Include the actual OTP in the email

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kevin.gangoso@gmail.com';
        $mail->Password = 'eqxmmmmfosdsrxny';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

        // Store the OTP in the session for verification in the next step
        // session_start();
        $_SESSION['otp'] = $otp;

        $msg = "Email sent successfully";
    } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>OTP Verification</title>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="submitotp.php" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP</label>
                    <input type="number" name="otp" id="otp" class="form-control" placeholder="Enter OTP" required>
                    <div class="invalid-feedback">
                        Please enter the OTP.
                    </div>
                </div>
                <input type="hidden" name="checkotp" id="checkotp" value="<?php echo isset($_POST['otp']) ? $_POST['otp'] : ''; ?>">
                <button type="submit" class="btn btn-primary">Verify</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
