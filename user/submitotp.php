<?php
require 'config.php';

$checkotp = $_POST['checkotp'];
$otp = $_POST['otp'];

if ($checkotp == $otp) {
    $resultMessage = "OTP Verify";
    // Redirect to register.php after a delay of 3 seconds
    header("refresh:3;url=login.php");
} else {
    $resultMessage = "Incorrect OTP";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<div class="container">
    <?php if ($checkotp != $otp) : ?>
        <!-- Display an error message and a form to retry OTP -->
        <div class="alert alert-danger" role="alert">
            <?php echo $resultMessage; ?> Please retry.
        </div>
        <form action="verify_otp.php" method="post">
            <input type="hidden" name="checkotp" value="<?php echo $checkotp; ?>">
            <input type="hidden" name="otp" value="<?php echo $otp; ?>">
            <button type="submit" class="btn btn-warning">Retry OTP</button>
        </form>
    <?php else : ?>
        <!-- Display success message -->
        <div class="alert alert-success" role="alert">
            <?php echo $resultMessage; ?> Redirecting to register page...
        </div>
        <!-- Display the "Login Now" button -->
        <a href="login.php" class="btn btn-primary mt-3">Login Now</a>
    <?php endif; ?>
</div>
</body>
</html>
