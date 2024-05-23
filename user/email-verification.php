<?php

// require 'config.php';

if(isset($_POST["verify_email"])) {
    $email = $_POST['email'];
    $verification_code = $_POST["verification_code"];

    $conn = mysqli_connect("localhost", "root", "P@55w0rd123", "register");

    $sql = "UPDATE acc SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" .$verification_code ."'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0) {
        header("Location: email-verification.php?error=Verification code is invalid");
        exit();
    }

    header("Location: login.php?success=Your email has been verified successfully");
    exit();
}

?>




<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <title>GrocerEase Stores</title>
    </head>

<style>
        .body{
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-image: url("image/homebg.jpg");
    background-repeat: no-repeat;
    align-items: center;
    justify-content: center;
    background-attachment: fixed;
  }

 .wrapper1{
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .wrapper{
    position: relative;
    height: 400px;
    width: 440px;
    margin-top: 8rem;
    background: transparent;
    border: 2px solid rgba(225, 225, 225, .5);
    backdrop-filter: blur(20px);
    box-shadow: 0 0 30px rgba(0, 0, 0, .5);
    align-items: center;
    justify-content: center;
    display: flex;
    overflow: hidden; 
    transition: height .2s ease;
    border-radius: 20px;
  }

  .wrapper .form-box{
    width: 100%;
    padding: 40px;
  }

  .wrapper .form-box.login{
   transition: transform .18s ease;
   transform: translateX(0);
  }

  .form-box h2{
    font-size: 2em;
    color: black;
    text-align: center;
  }

  .input-box{
    position: relative;
    width: 100%;
    height: 50px;
    border-bottom: 2px solid black;
    margin: 30px 0;
  }

  .input-box label{
    position: absolute;
    top: 50%;
    left: 5px;
    transform: translateY(-50%);
    font-size: 1em;
    color: black;
    font-weight: 500;
    pointer-events: none;
    transition: .5s;
  }
  
  .input-box input:focus~label,
  .input-box input:valid~label{
    top: -5px;
  }

  .input-box input{
    width: 100%;
    height: 100%;
    background: transparent;
    border: none;
    outline: none;
    font-size: 1em;
    color: black;
    font-weight: 600;
    padding: 0 35px 0 5px;
  }

  .input-box .icon{
    position: absolute;
    right: 8px;
    font-size: 1.2em;
    color: black;
    line-height: 57px;
  }

  .btnsub{
    width: 100%;
    height: 45px;
    background: #161616;
    border: none;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1em;
    color: white;
    font-weight: 500;
  }
</style>

    <body class="body">
            

        <div class="wrapper1">
            <div class="wrapper">
                <div class="form-box login">
                    <h2>Already verified? <a href="login.php">Login</a></h2>
                    <form class="" action="" method="post" autocomplete="off">
                        <div class="input-box">
                            <span class="icon"><i class="ri-mail-fill"></i></span>
                            <!-- <input type="hidden" name="usernameemail" required value="<?php echo $_GET['email']; ?>"> -->
                            <!-- <label for="usernameemail">Username or Email</label> -->
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class="ri-git-repository-private-fill"></i></span>
                            <input type="text" name="verification_code" placeholder="Enter Verification Code" value="" required>
                            <!-- <label for="password">Password</label> -->
                        </div>

                            <button type="submit" name="verify_email"  class="btnsub">Login</button>
                    </form>
                    <!-- <p>Don't Have an Account? <a href="register.php" class="login">Register</a></p> -->
                </div>
            </div>
        </div>
    </body>
</html>