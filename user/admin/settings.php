<?php
require 'config.php';

if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);

} else {
    header("Location: adminlogin.php");
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

        echo "<script> alert('Profile Updated Successfully'); </script>";
    }
}


// Fetch data for another heading from the database
$resultAnotherHeading = $conn->query("SELECT heading_content FROM content WHERE id = 2");

if ($resultAnotherHeading->num_rows > 0) {
    $rowAnotherHeading = $resultAnotherHeading->fetch_assoc();
    $currentAnotherHeading = $rowAnotherHeading['heading_content'];
} else {
    $currentAnotherHeading = "Default Another Heading";
}

// Fetch data for the existing heading from the database
$result = $conn->query("SELECT heading_content FROM content WHERE id = 1");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentHeading = $row['heading_content'];
} else {
    $currentHeading = "Default Heading";
}

// Handle the form submission for updating the heading
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the heading in the database based on the form submission
    if (isset($_POST["new_heading"])) {
        $newHeading = $_POST["new_heading"];

        // Update the heading in the database
        $updateHeadingQuery = "UPDATE content SET heading_content = '$newHeading' WHERE id = 1";
        if ($conn->query($updateHeadingQuery) === TRUE) {
            echo "<script>alert('Heading updated successfully');</script>";
            // Fetch the latest data after the update
            $result = $conn->query("SELECT heading_content FROM content WHERE id = 1");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentHeading = $row['heading_content'];
            }
        } else {
            echo "Error updating heading: " . $conn->error;
        }
    }

    // Update the content for another heading
    if (isset($_POST["new_another_heading"])) {
        $newAnotherHeading = $_POST["new_another_heading"];

        // Update the heading in the database
        $updateAnotherHeadingQuery = "UPDATE content SET heading_content = '$newAnotherHeading' WHERE id = 2";
        if ($conn->query($updateAnotherHeadingQuery) === TRUE) {
            echo "<script>alert('Another Heading updated successfully');</script>";
            // Fetch the latest data after the update
            $resultAnotherHeading = $conn->query("SELECT heading_content FROM content WHERE id = 2");
            if ($resultAnotherHeading->num_rows > 0) {
                $rowAnotherHeading = $resultAnotherHeading->fetch_assoc();
                $currentAnotherHeading = $rowAnotherHeading['heading_content'];
            }
        } else {
            echo "Error updating another heading: " . $conn->error;
        }
    }
}


// heading in grwoth company

// Fetch data for the additional heading from the database
$resultAdditionalHeading = $conn->query("SELECT heading_content FROM content WHERE id = 3");

if ($resultAdditionalHeading->num_rows > 0) {
    $rowAdditionalHeading = $resultAdditionalHeading->fetch_assoc();
    $currentAdditionalHeading = $rowAdditionalHeading['heading_content'];
} else {
    $currentAdditionalHeading = "Default Additional Heading";
}

// Handle the form submission for updating the additional heading
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the additional heading in the database based on the form submission
    if (isset($_POST["new_additional_heading"])) {
        $newAdditionalHeading = $_POST["new_additional_heading"];

        // Update the additional heading in the database
        $updateAdditionalHeadingQuery = "UPDATE content SET heading_content = '$newAdditionalHeading' WHERE id = 3";
        if ($conn->query($updateAdditionalHeadingQuery) === TRUE) {
            echo "<script>alert('Additional Heading updated successfully');</script>";
            // Fetch the latest data after the update
            $resultAdditionalHeading = $conn->query("SELECT heading_content FROM content WHERE id = 3");
            if ($resultAdditionalHeading->num_rows > 0) {
                $rowAdditionalHeading = $resultAdditionalHeading->fetch_assoc();
                $currentAdditionalHeading = $rowAdditionalHeading['heading_content'];
            }
        } else {
            echo "Error updating additional heading: " . $conn->error;
        }
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


// logo dynamic header

// Fetch the current site name from the database
$resultSiteName = $conn->query("SELECT site_name FROM content WHERE id = 1");

if ($resultSiteName->num_rows > 0) {
    $rowSiteName = $resultSiteName->fetch_assoc();
    $currentSiteName = $rowSiteName['site_name'];
} else {
    $currentSiteName = "Default Site Name";
}

// Handle the form submission for updating the site name
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the site name in the database based on the form submission
    if (isset($_POST["new_site_name"])) {
        $newSiteName = $_POST["new_site_name"];

        // Update the site name in the database
        $updateSiteNameQuery = "UPDATE content SET site_name = '$newSiteName' WHERE id = 1";
        if ($conn->query($updateSiteNameQuery) === TRUE) {
            echo "<script>alert('Site Name updated successfully');</script>";
            // Fetch the latest data after the update
            $resultSiteName = $conn->query("SELECT site_name FROM content WHERE id = 1");
            if ($resultSiteName->num_rows > 0) {
                $rowSiteName = $resultSiteName->fetch_assoc();
                $currentSiteName = $rowSiteName['site_name'];
            }
        } else {
            echo "Error updating site name: " . $conn->error;
        }
    }
}


// icon header dymanic

// Fetch the current icon code from the database
$resultIcon = $conn->query("SELECT icon_code FROM content WHERE id = 1");

if ($resultIcon->num_rows > 0) {
    $rowIcon = $resultIcon->fetch_assoc();
    $currentIconCode = $rowIcon['icon_code'];
} else {
    $currentIconCode = "ri-shopping-cart-2-fill"; // Default icon code
}

// Handle the form submission for updating the icon code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the icon code in the database based on the form submission
    if (isset($_POST["new_icon_code"])) {
        $newIconCode = $_POST["new_icon_code"];

        // Update the icon code in the database
        $updateIconCodeQuery = "UPDATE content SET icon_code = '$newIconCode' WHERE id = 1";
        if ($conn->query($updateIconCodeQuery) === TRUE) {
            echo "<script>alert('Icon code updated successfully');</script>";
            // Fetch the latest data after the update
            $resultIcon = $conn->query("SELECT icon_code FROM content WHERE id = 1");
            if ($resultIcon->num_rows > 0) {
                $rowIcon = $resultIcon->fetch_assoc();
                $currentIconCode = $rowIcon['icon_code'];
            }
        } else {
            echo "Error updating icon code: " . $conn->error;
        }
    }
}



// twitter dynamic

// Fetch the current Twitter link href from the database
$resultTwitterLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 3");

if ($resultTwitterLinkHref->num_rows > 0) {
    $rowTwitterLinkHref = $resultTwitterLinkHref->fetch_assoc();
    $currentTwitterLinkHref = $rowTwitterLinkHref['twitter_link_href'];
} else {
    $currentTwitterLinkHref = ""; // Set a default value if no data is found
}

// Edit Twitter Link form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_twitter_link_href"])) {
        $newTwitterLinkHref = $_POST["new_twitter_link_href"];

        // Update the Twitter link href in the database
        $updateTwitterLinkHrefQuery = "UPDATE content SET twitter_link_href = '$newTwitterLinkHref' WHERE id = 3";
        if ($conn->query($updateTwitterLinkHrefQuery) === TRUE) {
            echo "<script>alert('Twitter Link Href updated successfully');</script>";
            // Fetch the latest data after the update
            $resultTwitterLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 3");
            if ($resultTwitterLinkHref->num_rows > 0) {
                $rowTwitterLinkHref = $resultTwitterLinkHref->fetch_assoc();
                $currentTwitterLinkHref = $rowTwitterLinkHref['twitter_link_href'];
            }
        } else {
            echo "Error updating Twitter Link Href: " . $conn->error;
        }
    }
}


// facebook dynamic

// Fetch the current Facebook link href from the database
$resultFacebookLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 2");

if ($resultFacebookLinkHref->num_rows > 0) {
    $rowFacebookLinkHref = $resultFacebookLinkHref->fetch_assoc();
    $currentFacebookLinkHref = $rowFacebookLinkHref['twitter_link_href'];
} else {
    $currentFacebookLinkHref = ""; // Set a default value if no data is found
}

// Handle the form submission for updating the Facebook link href
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the Facebook link href in the database based on the form submission
    if (isset($_POST["new_facebook_link_href"])) {
        $newFacebookLinkHref = $_POST["new_facebook_link_href"];

        // Update the Facebook link href in the database
        $updateFacebookLinkHrefQuery = "UPDATE content SET twitter_link_href = '$newFacebookLinkHref' WHERE id = 2";
        if ($conn->query($updateFacebookLinkHrefQuery) === TRUE) {
            echo "<script>alert('Facebook Link updated successfully');</script>";
            // Fetch the latest data after the update
            $resultFacebookLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 2");
            if ($resultFacebookLinkHref->num_rows > 0) {
                $rowFacebookLinkHref = $resultFacebookLinkHref->fetch_assoc();
                $currentFacebookLinkHref = $rowFacebookLinkHref['twitter_link_href'];
            }
        } else {
            echo "Error updating Facebook link href: " . $conn->error;
        }
    }
}


// instagram dynamic
// Fetch the current Instagram link href from the database
$resultInstagramLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 1");

if ($resultInstagramLinkHref->num_rows > 0) {
    $rowInstagramLinkHref = $resultInstagramLinkHref->fetch_assoc();
    $currentInstagramLinkHref = $rowInstagramLinkHref['twitter_link_href'];
} else {
    $currentInstagramLinkHref = ""; // Set a default value if no data is found
}

// Handle the form submission for updating the Instagram link href
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the Instagram link href in the database based on the form submission
    if (isset($_POST["new_instagram_link_href"])) {
        $newInstagramLinkHref = $_POST["new_instagram_link_href"];

        // Update the Instagram link href in the database
        $updateInstagramLinkHrefQuery = "UPDATE content SET twitter_link_href = '$newInstagramLinkHref' WHERE id = 1";
        if ($conn->query($updateInstagramLinkHrefQuery) === TRUE) {
            echo "<script>alert('Instagram Link updated successfully');</script>";
            // Fetch the latest data after the update
            $resultInstagramLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 1");
            if ($resultInstagramLinkHref->num_rows > 0) {
                $rowInstagramLinkHref = $resultInstagramLinkHref->fetch_assoc();
                $currentInstagramLinkHref = $rowInstagramLinkHref['twitter_link_href'];
            }
        } else {
            echo "Error updating Instagram link href: " . $conn->error;
        }
    }
}

?>


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
            margin: 50px auto;
            max-width: 600px;
        }


        /* edit button css */
        .btne {
            background-color: #0d6efd;
            color: #ffffff;
            width: 100%;
            border: 1px solid #0d6efd;
            border-radius: 10px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btne:hover {
            background-color: #0a58ca;
            border-color: #0a58ca;
            color: #ffffff;
        }

                /* Update button css */
        .btnu {
            background-color: #0d6efd;
            color: #ffffff;
            margin: .3rem;
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

                /* close button css */
        .btnc {
            background-color: #0d6efd;
            color: #ffffff;
            margin: .3rem;
            width: 100%;
            border: 1px solid #0d6efd;
            border-radius: 10px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btnc:hover {
            background-color: #0a58ca;
            border-color: #0a58ca;
            color: #ffffff;
        }

        /* change heading css */

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

            /* Style for the textarea */
    #new_paragraph {
        width: 100%;
        height: 150px; /* Adjust the height as needed */
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    #new_another_paragraph{
        width: 100%;
        height: 150px; /* Adjust the height as needed */
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }
    #new_additional_paragraph{
        width: 100%;
        height: 150px; /* Adjust the height as needed */
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }


    .card-header {
        margin: 1rem;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        }

        .card-title {
            margin: 0;
        }

        .btn-primary {
            margin-left: 10px; /* Adjust the margin as needed */
            width: 20%;
        }
    
    /* edit profile css */

            /* Profile Card Styles */
            .profile-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        .profile-card strong {
            color: #333;
        }

        .mb-3 {
            margin-bottom: 15px;
        }
</style>



<?php include 'adminnavbar.php'; ?>


<div class="container wrapper12">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                <h2 class="card-title">My Profile</h2>
                
                <div class="card profile-card">
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

                                    <!-- Button trigger modal for edit profile -->
                <button type="button" class="btne" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Edit Profile
                </button>
                
                <br>
                <h4 class="text-center mb-4">Change Password</h4>
                    <!-- Button trigger modal -->
                    <button type="button" class="btne" data-bs-toggle="modal" data-bs-target="#password">
                        Change Password
                    </button>

                </div>


 


                    <!-- Modal edit profile modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                        

                        <!-- edit profile -->
                        <form action="" method="post" autocomplete="off">
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
                        <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
                        <button type="button" style="width: 100%;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                        </div>


                        </div>
                        </div>
                    </div>
                </div>
                <!-- end of edit profile modal -->


                <!-- Button trigger modal for change password -->
                <div class="card profile-cards mt-4">

                </div>


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
                                <button type="submit" name="updatep" class="btn btn-primary">Update Password</button>
                                <button type="button" style="width: 100%;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>

                                </div>
                            
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of modal change password -->

                
                            <!-- dynamic content -->
                        <div class="card-header">
                        <h2>Edit First Heading</h2>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <label for="new_heading">Edit First Heading:</label>
                                <input type="text" id="new_heading" name="new_heading" value="<?php echo $currentHeading; ?>">
                                <input type="submit" value="Update">
                            </form>
                        </div>

                        <div class="card-header">
                        <h2>Edit Second Heading</h2>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <label for="new_another_heading">Edit Second Heading:</label>
                                <input type="text" id="new_another_heading" name="new_another_heading" value="<?php echo $currentAnotherHeading; ?>">
                                <input type="submit" value="Update">
                            </form>
                        </div>



                        <!-- site header dymanic -->
                        <div class="card-header">
                            <h2>Edit Site Name</h2>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <label for="new_site_name">Edit Site Name:</label>
                                <input type="text" id="new_site_name" name="new_site_name" value="<?php echo $currentSiteName; ?>">
                                <input type="submit" value="Update">
                            </form>
                        </div>

                        <!-- icon dynamic -->
                        <div class="card-header">
                        <h2>Edit Icon Code</h2>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <label for="new_icon_code">Edit Icon Code:</label>
                            <input type="text" id="new_icon_code" name="new_icon_code" value='<?php echo $currentIconCode; ?>'>
                            <input type="submit" value="Update">
                        </form>
                        </div>


                        <!-- twitter dynamic -->
                        <!-- Edit Twitter Link form -->
                        <div class="card-header">
                            <h2>Edit Twitter Link</h2>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <label for="new_twitter_link_href">Edit Twitter Link Href:</label>
                                <input type="text" id="new_twitter_link_href" name="new_twitter_link_href" value='<?php echo $currentTwitterLinkHref; ?>'>
                                <input type="submit" value="Update Twitter Link">
                            </form>
                        </div>

                        <!-- facebook dynamic -->
                        <!-- Edit Facebook Link form -->
                        <div class="card-header">
                            <h2>Edit Facebook Link</h2>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <label for="new_facebook_link_href">Edit Facebook Link Href:</label>
                                <input type="text" id="new_facebook_link_href" name="new_facebook_link_href" value='<?php echo htmlspecialchars($currentFacebookLinkHref); ?>'>
                                <input type="submit" value="Update Facebook Link">
                            </form>
                        </div>

                        <!-- instagram dynamic -->
                        <!-- Edit Instagram Link form -->
                        <div class="card-header">
                            <h2>Edit Instagram Link</h2>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <label for="new_instagram_link_href">Edit Instagram Link Href:</label>
                                <input type="text" id="new_instagram_link_href" name="new_instagram_link_href" value='<?php echo htmlspecialchars($currentInstagramLinkHref); ?>'>
                                <input type="submit" value="Update Instagram Link">
                            </form>
                        </div>


            </div>
        </div>
    </div>
</div>


<script>
        function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
