<?php



require 'config.php';



function is_valid_number($number) {
    // Check if the number has exactly 11 digits
    return preg_match('/^\d{11}$/', $number);
}



if (isset($_POST["submit"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $user_type = $_POST['user_type'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Get the user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];

    


      
    // Check for duplicate username
    $duplicate = mysqli_query($conn, "SELECT * FROM acc WHERE username = '$username'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert ('Username Has Already Taken'); </script>";
    } else {
        if ($password == $confirmpassword) {
            if (is_valid_number($number)) {
                // Hash the password
                $hash_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the hashed password into the database along with the IP address
                $query = "INSERT INTO acc(`fname`, `lname`, `username`, `email`, `password`, `usertype`, `address`, `number`, `otp`) VALUES('$fname', '$lname',    '$username', '$email', '$hash_password', '$user_type', '$address', '$number', '$otp')";
                mysqli_query($conn, $query);


            

          
                echo "<script>";
                echo "alert('Registration Successful');";
                echo "window.location.href = 'login.php';";
                echo "</script>";
            } else {
                echo "<script> alert('Please enter a valid 11-digit number.'); </script>";
            }
        } else {
            echo "<script> alert('Password Does Not Match'); </script>";
        }
    }


}
?>





      
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
        <title>Registration</title>


<style>
        body{
        height: 100vh;
        background-size: cover;
        background-position: center;
        background-image: url("image/homebg.jpg");
        background-repeat: no-repeat;
        align-items: center;
        justify-content: center;
        background-attachment: fixed;

        }

        .wrapper12{
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .wrapper2{
        position: relative;
        height: 820px;
        width: 440px;
        margin-top: 2rem;
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

        .wrapper2 .form-box{
        width: 100%;
        padding: 40px;
        }

        .wrapper2 .form-box.login{
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
        height: 45px;
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

        .wrapper2 .form-box{
            width: 100%;
            padding: 40px;
        }

        .wrapper2 .form-box.register{
        transition: transform .18s ease;
        transform: translateX(0);
        }

        .form-box h2{
            font-size: 2em;
            color: black;
            text-align: center;
        }

        .wrapper12{
            display: flex;
            justify-content: center;
            align-items: center;
        }



            .user{
            display: none;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
            text-align: center;
            }

            .wrapper2 select{
            width: 100%;
            padding: 10px 15px;
            font-size: 17px;
            margin: 8px 0;
            background: #eee;
            display: none;
            }
</style>

    </head>
    <body>

    

        <div class="wrapper12">
            <div class="wrapper2">
                <div class="form-box register">
                    <h2>Registration</h2>
                        <form class="" action="" method="post" autocomplete="off">  <!-- action="verify_otp.php"; new -->

                            <div class="input-box">
                                <span class="icon"><i class="ri-shield-user-fill"></i></span>
                                <input type="text" name="fname" id="fname" required value="<?php echo isset($fname) ? $fname : ''; ?>">
                                <label for="name">First Name: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-shield-user-fill"></i></span>
                                <input type="text" name="lname" id="lname" required value="<?php echo isset($lname) ? $lname : ''; ?>">
                                <label for="name">Last Name: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-user-fill"></i></i></span>
                                <input type="text" name="username" id="username" required value="<?php echo isset($username) ? $username : ''; ?>">
                                <label for="username">Username: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-mail-fill"></i></span>
                                <input type="email" name="email" id="email" required value="<?php echo isset($email) ? $email : ''; ?>">
                                <input type="hidden" name="otp" value="<?php echo $otp; ?>">    
                                <!-- new -->
                                <label for="email">Email: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-mail-fill"></i></span>
                                <input type="number" name="number" id="number" required value="<?php echo isset($number) ? $number : ''; ?>">
                                <label for="email">Contact Number: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-mail-fill"></i></span>
                                <input type="address" name="address" id="address" required value="<?php echo isset($address) ? $address : ''; ?>">
                                <label for="email">Address: </label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="ri-git-repository-private-fill"></i></span>
                                <input type="password" name="password" id="password" required value="<?php echo isset($password) ? $password : ''; ?>">
                                <label for="password">Password: </label>
                            </div>

                            <div class="input-box">    
                                <span class="icon"><i class="ri-git-repository-private-fill"></i></span>
                                <input type="password" name="confirmpassword" id="confirmpassword" required value="<?php echo isset($confirmpassword) ? $confirmpassword : ''; ?>">
                                <label for="password">Confirm Password: </label>
                            </div>

                            <select name="user_type" id="">
                                <option value="user"></option>
                            </select>
                        

                                <!-- Data Policy Section -->
                                <div class="data-policy-section">
                                    <input type="checkbox" id="dataPolicyCheckbox" name="dataPolicyCheckbox" required>
                                    <label for="dataPolicyCheckbox">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Data Policy & Term & Conditions</a></label>
                                </div>

                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Launch demo modal
                                </button> -->

                                



                            <button type="submit" name="submit" class="btnsub">Register</button>
                        </form>
                            <p>Already Have an Account? <a href="login.php">Login</a></p>

                </div>
            </div>
        </div>



                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Data Policy & Term and Conditions</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                    <!-- Term and Conditions -->
                                        <h5>Welcome to KCJ GROCERY. By accessing this website, you agree to comply with and be bound by the following terms and conditions of use. If you do not agree with any part of these terms, please do not use our website.</h5>
                                        <br>
                                        <h5>The use of this website is subject to the following terms:</h5>

                                        <h5><strong>1. Content and Intellectual Property:</strong></h5>
                                        <p>All content on this website, including text, graphics, logos, images, and software, is the property of Tina Berin Flowershop and is protected by copyright laws. Unauthorized use of this content may violate copyright and other laws.</p>
                                        <br>
                                        <h5><strong>2. User Conduct:</strong></h5>
                                        <p>When using our website, you agree not to engage in any conduct that may restrict or inhibit others from using the site. Prohibited activities include violating applicable laws, infringing on intellectual property rights, and distributing harmful software or content.</p>
                                        <br>
                                        <h5><strong>3. Privacy Policy:</strong></h5>
                                        <p>Your use of our website is also governed by our Privacy Policy. Please review the Privacy Policy to understand how we collect, use, and protect your personal information.</p>
                                        <br>
                                        <h5><strong>4. Limitation of Liability:</strong></h5>
                                        <p>Tina Berin Flowershop shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your access to or use of the website. This includes any errors or omissions in the content.</p>
                                        <br>
                                        <h5><strong>5. Changes to Terms:</strong></h5>
                                        <p>We reserve the right to modify these terms and conditions at any time. It is your responsibility to regularly review the terms. Continued use of the website after changes constitutes acceptance of the modified terms.</p>
                                        <br>
                                        <h5><strong>6. Governing Law:</strong></h5>
                                        <p>These terms and conditions are governed by and construed in accordance with the laws of [Your Jurisdiction]. Any disputes relating to these terms shall be subject to the exclusive jurisdiction of the courts in [Your Jurisdiction].</p>



                                    <!-- Data Policy -->
                                        <h5>Welcome to KCJ GROCERY. Your privacy is important to us. This Privacy Policy outlines how we collect, use, and protect your personal information. By using our website, you consent to the practices described in this policy.</h5>
                                        <br>
                                        <h5><strong>1. Information We Collect:</strong></h5>
                                        <p>We may collect personal information such as your name, email address, phone number, and address for the purpose of providing our services. We do not share this information with third parties unless required by law.</p>
                                        <br>
                                        <h5><strong>2. How We Use Your Information:</strong></h5>
                                        <p>We use the collected information to process orders, improve our services, and communicate with you. We may also use your email address to send promotional emails and updates about our products and services.</p>
                                        <br>
                                        <h5><strong>3. Security:</strong></h5>
                                        <p>We take appropriate measures to protect your personal information. However, no method of transmission over the internet or electronic storage is entirely secure. We strive to use commercially acceptable means to protect your information but cannot guarantee its absolute security.</p>
                                        <br>
                                        <h5><strong>4. Your Choices:</strong></h5>
                                        <p>You have the right to access, correct, or delete your personal information. You may also opt out of receiving promotional emails by following the unsubscribe instructions provided in the email.</p>
                                        <br>
                                        <h5><strong>5. Changes to Privacy Policy:</strong></h5>
                                        <p>We reserve the right to update this Privacy Policy at any time. Changes will be posted on this page, and your continued use of our website after modifications constitute acceptance of the updated policy.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <!--<button type="button" class="btn btn-primary">Agree</button>-->
                                    </div>
                                    </div>
                                </div>
                            </div>

    </body>
</html>