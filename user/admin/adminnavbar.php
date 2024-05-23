<?php
// require 'config.php';

if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);

} else {
    header("Location: adminlogin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admincss.css">
        <!-- Include jQuery and DataTables CSS and JavaScript from CDN -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->
</head>
<style>


    .side-menu {
        /* width: 250px; */
        height: 100vh;
        background-color: #3B3B3B;
        color: #fff;
        padding-top: 20px;
    }

    .store-name {
        text-align: center;
        padding: 10px 0;
    }

    .store-name img {
        height: 30px;
        width: 30px;
        /* margin-bottom: 10px; */
    }

    .store-name h3 {
        margin: 0;
        text-transform: capitalize;
    }

    .store-name hr {
        border: 2px solid #fff;
        width: 50%;
        margin: auto;
    }

    .side-menu ul {
        list-style: none;
        padding: 0;
        margin: 2rem;
    }

    .side-menu li {
        padding: 1rem;
    }

    .side-menu a {
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .side-menu i {
        margin-right: 10px;
    }
</style>
<body>
    <div class="side-menu">
        <div class="store-name">
            <img src="image/user.png" alt="">
            <h3><?php echo $row["fname"]; ?></h3>
            <hr>
        </div>
        <ul>
            <li><a href="admin.php"><i class="ri-dashboard-fill"></i><span> DashBoard</span></a></li>
            <li><a href="adminuser.php"><i class="ri-user-fill"></i><span> Users</span></a></li>
            <li><a href="adminproduct.php"><i class="ri-shopping-cart-2-fill"></i><span> Product</span></a></li>
            <li><a href="orderlist.php"><i class="bi bi-card-list"></i><span> Order List</span></a></li>
            <li><a href="sales.php"><i class="bi bi-coin"></i><span> Sales Report</span></a></li>
            <li><a href="settings.php"><i class="ri-settings-2-line"></i><span> Settings</span></a></li>
            <li><a href="adlogout.php"><i class="ri-logout-box-r-line"></i><span> Log Out</span></a></li>
        </ul>
    </div>



    <!-- <div class="collapse" id="navbarToggleExternalContent">
  <div class="bg-dark p-4">
    <h5 class="text-white h4">Collapsed content</h5>
    <span class="text-muted">Toggleable via the navbar brand.</span>
  </div>
</div>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
