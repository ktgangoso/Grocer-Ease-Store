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

        echo "<script> alert('Profile Updated Successfully'); </script>";
    }
}

// logo dynamic

// Fetch the current site name from the database
$resultSiteName = $conn->query("SELECT site_name FROM content WHERE id = 1");

if ($resultSiteName->num_rows > 0) {
    $rowSiteName = $resultSiteName->fetch_assoc();
    $currentSiteName = $rowSiteName['site_name'];
} else {
    $currentSiteName = "Grocer Ease";
}


// icon header dynamic

// Fetch the current icon code from the database
$resultIcon = $conn->query("SELECT icon_code FROM content WHERE id = 1");

if ($resultIcon->num_rows > 0) {
    $rowIcon = $resultIcon->fetch_assoc();
    $currentIconCode = $rowIcon['icon_code'];
} else {
    $currentIconCode = '<i class="ri-shopping-cart-2-fill"></i>'; // Default icon code
}

?>

<!DOCTYPE html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Grocer Ease</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="style.css">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
       
       body {
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-color: #DDE6ED;
    background-repeat: no-repeat;
    align-items: center;
    justify-content: center;
    padding-top: 70px; /* Adjust this value based on the height of your navbar */
  }

  .container {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .content {
    text-align: center;
    font-family: Arial, sans-serif;
    position: relative;
    margin-top: 100px;
  }

  h1 {
    font-size: 48px;
    margin-bottom: 20px;
    color: #333;
  }

  p {
    font-size: 20px;
    color: #000000;
  }

  .btn1 {
    display: inline-block;
    padding: 12px 24px;
    font-size: 20px;
    text-decoration: none;
    background-color: #F39C12;
    color: #000000;
    border-radius: 4px;
    transition: background-color 0.3s;
  }

  .btn1:hover {
    background-color: #FAB953;
  color: #FFFFFF;
  }
    
    /* cart css */

    /* Style for the cart button */
    .btncart {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #fff; /* Change to your desired text color */
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    border: 1px solid #FFFFFF; /* Change to your desired border color */
    padding: 5px 10px; /* Adjust padding as needed */
    font-size: 1rem;
    border-radius: 5px; /* Add border-radius for rounded corners */
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
   background-color: var(--white);
  }

  .btncart:hover {
      background-color: #000000; /* Change to your desired background color on hover */
      border-color: #FFFFFF; /* Change to your desired border color on hover */
  }

  /* Optional: Adjust the icon style */
  .ri-shopping-cart-2-fill {
      margin-right: 5px; /* Add space between icon and text */
  }

  /* Optional: Style for the button text */
  .btncart span {
      font-size: 12px;
      margin-right: 5px; /* Add space between icon and text */
  }

  /* modal button style */
  .btn-primary {
            background-color: #007bff;
            border: #007bff;
  margin: .5rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border: #0056b3;
        }
    .btn-secondary{
    margin: .5rem;
    }

    .modal-header{
    margin-top: 1.5rem;
    }
    
    .navbar {
    /* Add a border */
    border: 1px solid #ccc;
    
    /* Add a shadow */
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* You can adjust the values as per your requirement */
  }
</style>
  
<body>
<!-- ri-shopping-cart-2-fill -->
<!-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> -->

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-3 fixed-top">
    
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php#hero"><?php echo $currentIconCode; ?></i><?php echo $currentSiteName; ?></a>
        <!-- <a class="navbar-brand" href="index.php#hero" ><img src="image/kcjg.png" alt="" height="50px" width="250px"></a> -->
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                <li class="nav-item">
                        <a href="index.php"  class="nav-link">Home</a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="store.php"  class="nav-link">Product</a>
                    </li>

                    
                    <!-- <li class="nav-item">
                        <a href="profile.php" class="nav-link">Profile</a>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#exampleModal">Profile</a>
                    </li> -->
                    

                    <li class="nav-item">                          
                          <a href="myorder.php" class="nav-link">My Order</a> 
                    </li>

                    
                </ul>
                <ul class="nav navbar-nav ms-auto">

                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $userData["fname"]; ?> <?php echo $userData["lname"]; ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a href="profile.php" class="dropdown-item"><i class="bi bi-person-circle"></i> Profile</a></li>
                    <!--<li><a class="dropdown-item" href="index.php#team"><i class="bi bi-people-fill"></i> Team</a></li>-->
                    <li><hr class="dropdown-divider"></li>
                    <li><a href="logout.php" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Log Out</a></li>
                </ul>
                </li>


                <li class="nav-item dropdown">
                <?php
                // Assuming you have the user_id stored in the session
                $user_id = $_SESSION['user_id'];

                // Query to get the count of items in the user's cart
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
                ?>

                <a href="cart.php" class="btncart btn-outline-dark">
                        <span style="font-size: 12px;" class="mb-5"><?php echo $row_count; ?></span> <i class="bi bi-cart4"></i>
                </a>
                </li>

 
                <!-- <li class="nav-item">                          
                          <a href="myorder.php" class="nav-link">My Order</a> 
                          <a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Log Out</a> 
                    </li> -->


                        <!-- <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" ><i class="ri-settings-3-fill"></i></a>
                        <div class="dropdown-menu">
                          <a href="logout.php" class="dropdown-item">Log Out</a> 
                        </div> -->


            
                </ul>
            </div>
        </div>
    </nav>   



<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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

                    <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div> -->
                      

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>