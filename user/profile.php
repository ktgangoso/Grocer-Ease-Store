<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

// Fetch user data from the database based on the user ID
$query = "SELECT * FROM acc WHERE user_id = $id";
$result = mysqli_query($conn, $query);
$userData = mysqli_fetch_assoc($result);

if (!$userData) {
    // Handle the case where the user data is not found
    // You may redirect the user or display an error message
}

if (isset($_POST["update"])) {
    // Process the form submission to update user information
    $newFirstName = $_POST["new_fname"];
    $newLastName = $_POST["new_lname"];
    $newEmail = $_POST["new_email"];
    $newAddress = $_POST["new_address"];
    
    // Validate and ensure the number is exactly 11 digits
    $newNumber = $_POST["new_number"];
    if (strlen($newNumber) !== 11) {
        echo "<script> alert('Number must be exactly 11 digits.'); </script>";
    } else {
        // Update the user information in the database
        $updateQuery = "UPDATE acc SET fname = '$newFirstName', lname = '$newLastName', email = '$newEmail', address = '$newAddress', number = '$newNumber' WHERE user_id = $id";
        mysqli_query($conn, $updateQuery);

        // echo "<script> alert('Profile Updated Successfully'); </script>";
    }
}

// change password

if (isset($_POST["updatep"])) {
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check if the current password is correct
    // (You should implement a secure password verification method here)
    if (password_verify($currentPassword, $userData["password"])) {

        // Validate the new password and confirm password
        if ($newPassword === $confirmPassword) {
            // Hash the new password before storing it in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the user's password in the database
            $updatePasswordQuery = "UPDATE acc SET password = '$hashedPassword' WHERE user_id = $id";
            mysqli_query($conn, $updatePasswordQuery);

            echo "<script> alert('Password Updated Successfully'); </script>";
        } else {
            echo "<script> alert('New password and confirm password must match.'); </script>";
        }
    } else {
        echo "<script> alert('Incorrect current password.'); </script>";
    }
}


?>

  

<?php include 'navbar.php';?>
<!-- <style>
  body{
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
  height: 620px;
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
</style> -->

<style>
    /* Add your custom styles here */
    .wrapper12 {
    margin: 20px auto;
    max-width: 100%;
    }

    .card {
        margin-top: 1rem;
        width: 70%;
        margin-left: auto;
        margin-right: auto;
    }

    .card-title {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    /* .input-group-prepend .input-group-text {
        background-color: #007bff;
        border: #007bff;
        color: #ffffff;
    } */

    .form-control {
        border-radius: 0;
    }

    .btn-primary {
        background-color: #007bff;
        border: #007bff;
        border-radius: 0;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border: #0056b3;
    }

    /* edit button css */
    .btnu {
    background-color: #0d6efd;
    color: #ffffff;
    width: 100%;
    border: 1px solid #0d6efd;
    border-radius: 10px;
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btnu:hover {
        background-color: #0a58ca;
        border-color: #0a58ca;
        color: #ffffff;
    }

</style>
<style>

        .profile-card {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-cards {
            max-width: 100%;
            width: 100%;
            /* margin: 50px auto; */
            margin-left: 1rem;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-profile-btn {
            width: 100%;
            margin-top: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        /* responsive */
        /* Adjustments for larger screens */
        @media (min-width: 768px) {
            .profile-card,
            .profile-cards {
                width: 48%; /* Two cards side by side */
            }
        }

        /* Adjustments for smaller screens */
        @media (max-width: 576px) {
            .profile-card,
            .profile-cards {
                width: 100%; /* Full width for smaller screens */
                margin-right: 0; /* Remove right margin to prevent spacing issues */
            }
        }

</style>

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">My Profile</h2>


                    <form action="" method="post" autocomplete="off">

                    <div class="container">
                        <div class="card profile-card">
                            <h4 class="text-center mb-4">User Profile</h4>
                            <div class="mb-3">
                                <strong>First Name:</strong> <?php echo $userData["fname"]; ?>
                            </div>
                            <div class="mb-3">
                                <strong>Last Name:</strong> <?php echo $userData["lname"]; ?>
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> <?php echo $userData["email"]; ?>
                            </div>
                            <div class="mb-3">
                                <strong>Contact Number:</strong> <?php echo $userData["number"]; ?>
                            </div>
                            <div class="mb-3">
                                <strong>Address:</strong> <?php echo $userData["address"]; ?>
                            </div>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Edit Profile
                            </button>
                        </div>


                        <div class="card profile-cards mt-4">
                            <h4 class="text-center mb-4">Change Password</h4>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#password">
                                Change Password
                            </button>
                        </div>

                    </div>

                    






                    <!-- Modal edit profile -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit My Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
        
      
                    <form action="">
                        <!-- edit user profile  -->
                        <div class="form-group">
                            <label for="new_fname">First Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ri-shield-user-fill"></i></span>
                                </div>
                                <input type="text" class="form-control" name="new_fname" id="new_fname" required value="<?php echo $userData['fname']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_lname">Last Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ri-shield-user-fill"></i></span>
                                </div>
                                <input type="text" class="form-control" name="new_lname" id="new_lname" required value="<?php echo $userData['lname']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_email">Email:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ri-mail-fill"></i></span>
                                </div>
                                <input type="email" class="form-control" name="new_email" id="new_email" required value="<?php echo $userData['email']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_number">Number:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ri-contacts-fill"></i></span>
                                </div>
                                <input type="number" class="form-control" name="new_number" id="new_number" required value="<?php echo $userData['number']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_address">Address:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ri-map-pin-fill"></i></span>
                                </div>
                                <input type="text" class="form-control" name="new_address" id="new_address" required value="<?php echo $userData['address']; ?>">
                            </div>
                        </div>





                        <div class="modal-footer">
                        <button type="submit" name="update" class="btnu btn-primary">Update Profile</button>
                        <button type="button" style="width: 100%;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                        </div>
                        </form>
                        </div>
                    </div>
                </div>
                    <!-- end of edit profile -->

                        

                <!-- Modal change password -->
                <div class="modal fade" id="password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
        
      

                        <form method="post" action="">

                            <!-- edit user password -->
                            <div class="form-group">
                                <label for="current_password">Current Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ri-shield-user-fill"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" onclick="togglePassword('current_password')">
                                            <i class="ri-eye-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- new password -->
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ri-shield-user-fill"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" onclick="togglePassword('new_password')">
                                            <i class="ri-eye-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- confirm password -->
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ri-shield-user-fill"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" onclick="togglePassword('confirm_password')">
                                            <i class="ri-eye-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>






                            <div class="modal-footer">
                            <button type="submit" name="updatep" class="btnu btn-primary">Update Password</button>
                            <button type="button" style="width: 100%;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                            </div>
                        
                        </form>
                        </div>
                    </div>
                </div>
                        <!-- end of change password -->


                    </form>
                </div>
            </div>
        </div>
    </div>



</body>
</html>

<script>
        function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>
   <!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
