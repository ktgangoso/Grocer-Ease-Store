<?php
require 'config.php';

// Assuming you have a function named searchProducts
function searchProducts($conn, $searchTerm) {
    $query = "SELECT * FROM `acc` WHERE user_id LIKE '%$searchTerm%' OR fname LIKE '%$searchTerm%' OR lname LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR usertype LIKE '%$searchTerm%'";
    return mysqli_query($conn, $query);
}

if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: adminlogin.php");
}

// Search functionality
if (isset($_GET['submit']) && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $select_user = searchProducts($conn, $searchTerm);
} else {
    $select_user = mysqli_query($conn, "SELECT * FROM `acc`");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="admincss.css">
    <title>Admin</title>
        <!-- Include DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
</head>
    <body>
    <?php include 'adminnavbar.php'; ?>

<style>
      .display-product-table table {
        width: 95%;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
      }

      .display-product-table th,
      .display-product-table td {
        padding: 10px 20px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        text-align: center;
      }

      .display-product-table thead {
        background-color: #007BFF;
        color: #fff;
      }

      .display-product-table th {
        font-weight: bold;
      }

    .display-product-table tbody tr:hover {
        background-color: #999;
        cursor: pointer;
    }
      .empty {
        text-align: center;
        padding: 10px;
        color: #999;
      }

      /* search style */
      /* Add this to your existing styles or in a separate CSS file */

        .form-inline {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
            margin-right: 6rem;
        }

        .search-container {
            display: flex;
        }


        .btn-primary {
            border-radius: 0;
            width: 5rem;
        }

        /* .btns {
        display: inline-block;
        padding: 10px 16px;
        margin-left: 1rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        user-select: none;
        border: 1px solid transparent;
        border-radius: 10px;
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btns:hover {
            background-color: #0056b3;
            color: #fff;
        } */

        .display-product-table {
            margin-top: 20px;
        }

        .not-found {
            text-align: center;
            font-style: italic;
        }

        .container {
            background-color: #f8f9fa; /* Replace with your desired color code */
            padding: 20px; /* Optional: Add padding to the container */
            border-radius: 20px;

        }

        /* Add your custom styles here if needed */
        .custom-container {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            height: 90%;
            margin-right: 20px; /* Adjust the left margin as needed */
            margin-bottom: 20px; /* Adjust the bottom margin as needed */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }
        
</style>
        
<div class="container mt-5 custom-container">


        <?php
// Assuming $select_user is an array of all users
$currentUsers = mysqli_fetch_all($select_user, MYSQLI_ASSOC);
?>

<!-- <div class="container"> -->
    <section class="display-product-table">
        <table class="table table-hover" id="userTable">
            <thead class="thead-dark">
                <tr>
                    <th>User ID No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>User Type</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($currentUsers)) {
                foreach ($currentUsers as $row) {
            ?>
                    <tr>
                        <td data-cell="User ID no."><?php echo $row['user_id']; ?></td>
                        <td data-cell="Name"><?php echo $row['fname']; ?></td>
                        <td data-cell="Name"><?php echo $row['lname']; ?></td>
                        <td data-cell="Number"><?php echo $row['number']; ?></td>
                        <td data-cell="Email"><?php echo $row['email']; ?></td>
                        <td data-cell="User Type"><?php echo $row['usertype']; ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='not-found'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>


</section>

    </div>

    <!-- Include jQuery and DataTables JS -->
<!-- Include jQuery before DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        $('#userTable').DataTable();
    });
</script>


    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="scripts.js"></script>
    </body>
</html>