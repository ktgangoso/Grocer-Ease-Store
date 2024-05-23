<?php
require 'config.php';
if(!empty($_SESSION["user_id"])){
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
}
else{
    header("Location: adminlogin.php");
}
// update customer
// Handle update and delete actions
if (isset($_POST["action"])) {
    $orderId = $_POST["order_id"];
    $action = $_POST["action"];

    if ($action === "update") {
        $newUpdateStatus = $_POST["update_status"];
        // Perform an SQL update to change the payment status for the given order
        $updateQuery = "UPDATE `order` SET updates = '$newUpdateStatus' WHERE id = $orderId";
        mysqli_query($conn, $updateQuery);
    } elseif ($action === "delete") {
        // Perform an SQL delete to remove the order
        $deleteQuery = "DELETE FROM `order` WHERE id = $orderId";
        mysqli_query($conn, $deleteQuery);
    }
}

// Fetch the customer orders
$select_products = mysqli_query($conn, "SELECT * FROM `order`");

// Initialize total income for delivered orders
$totalDeliveredIncome = 0;

while ($row = mysqli_fetch_assoc($select_products)) {
    // Display the orders in the table

    // ...

    // Check if the order has the status "Delivered"
    if ($row['updates'] === 'Delivered') {
        // Add the income to the total for delivered orders
        $totalDeliveredIncome += $row['total_price'];
    }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Include jQuery and DataTables CSS and JavaScript from CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


    <link rel="stylesheet" href="admincss.css">
    <title>Admin</title>
    </head>

<style>
    .container {
        text-align: center;
        text-transform: capitalize;
    }

    .order {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        margin: 10px;
        width: 30%;
    }

    .order p {
        margin: 2px;
    }

    .update {
        margin-top: 10px;
        text-align: center;
    }

    .cancel{
        border: 2px solid black;
        text-align: center;
        margin: 10px;
        padding: 5px;
    }

    select {
        width: 100%;
        padding: 5px;
    }

    .a {
        display: inline-block;
        background-color: #007BFF;
        color: #fff;
        text-align: center;
        padding: 5px 10px;
        text-decoration: none;
        margin: 10px 0;
        border-radius: 5px;
    }

    .empty {
        text-align: center;
        width: 100%;
        font-weight: bold;
        color: #007BFF;
        font-size: 5rem;
    }

    /* update button css */
    .btn-group{
        width: 100%;
    }

    /* view Button css */
    .btnv {
    background-color: #0d6efd;
    color: #ffffff;
    width: 80%;
    border: 1px solid #0d6efd;
    border-radius: 10px;
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btnv:hover {
        background-color: #0a58ca;
        border-color: #0a58ca;
        color: #ffffff;
    }

    /* update button css */
    .btnu {
        background-color: #0d6efd;
        color: #ffffff;
        width: 80%;
        margin: .5rem;
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

    /* delete button css */
    .btnd {
        background-color: #0d6efd;
        color: #ffffff;
        width: 80%;
        margin: .5rem;
        border: 1px solid #0d6efd;
        border-radius: 10px;
        padding: 8px 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btnd:hover {
        background-color: #0a58ca;
        border-color: #0a58ca;
        color: #ffffff;
    }

    /* close button css */
    .btnc {
        background-color: #0d6efd;
        color: #ffffff;
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

    
</style>

    <body>
<?php include 'adminnavbar.php'; ?>




<!-- Customer Receipt -->
<!-- Display the customer orders -->
<!-- <div class="container mt-5 d-flex justify-content-center align-items-center flex-column"> -->
    <?php
    $results_per_page = 10; // Number of orders to display per page

    // Assuming you have a database connection and your SELECT query is correctly set up
    $select_products = mysqli_query($conn, "SELECT * FROM `order` ORDER BY `order_date` DESC");

    if (mysqli_num_rows($select_products) > 0) {
    ?>
<div class="container mt-4">
    <div class="table-responsive">
        <table id="orderTable" class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order Date</th>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Customer Order</th>
                    <th>View Order</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($select_products)) { ?>
                    <tr>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['updates']; ?></td>
                        <td>
                            <button type="button" class="btnv btn-primary custom-view-button" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']; ?>">
                                View
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Order Date: <?php echo $row['order_date']; ?></h5>
                                            <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Customer Information</h5>
                                                <form>
                                                    <div class="form-group">
                                                        <label for="customerId">Customer ID:</label>
                                                        <input type="text" class="form-control" id="customerId" value="<?php echo $row['id']; ?>"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerName">Name:</label>
                                                        <input type="text" class="form-control" id="customerName" value="<?php echo $row['name']; ?>"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerNumber">Number:</label>
                                                        <input type="text" class="form-control" id="customerNumber"
                                                            value="<?php echo $row['number']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerEmail">Email:</label>
                                                        <input type="text" class="form-control" id="customerEmail"
                                                            value="<?php echo $row['email']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerPayment">Payment:</label>
                                                        <input type="text" class="form-control" id="customerPayment"
                                                            value="<?php echo $row['payment']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerCancellationReason">Customer Reason for Cancellation:</label>
                                                        <input type="text" class="form-control" id="customerCancellationReason"
                                                            value="<?php echo $row['cancellation_reason']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerUpdates">Update:</label>
                                                        <input type="text" class="form-control" id="customerUpdates" value="<?php echo $row['updates']; ?>"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerAddress">Address:</label>
                                                        <input type="text" class="form-control" id="customerAddress"
                                                            value="<?php echo $row['address']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerTotalProducts">Products:</label>
                                                        <input type="text" class="form-control" id="customerTotalProducts"
                                                            value="<?php echo $row['total_products']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="customerTotalPrice">Total Price:</label>
                                                        <input type="text" class="form-control" id="customerTotalPrice"
                                                            value="₱ <?php echo $row['total_price']; ?>" readonly>
                                                    </div>
                                                </form>
                                            </div>


                                            <form method="post" class="mt-3">
                                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                <div class="btn-group">
                                                    <select name="update_status" class="form-select">
                                                        <option value="Pending Order" <?php if ($row['updates'] === 'Pending Order') echo 'selected'; ?>>Pending Order</option>
                                                        <option value="Ready to Pick Up" <?php if ($row['updates'] === 'Ready to Pick Up') echo 'selected'; ?>>Ready to Pick Up</option>
                                                        <option value="To Ship" <?php if ($row['updates'] === 'To Ship') echo 'selected'; ?>>To Ship</option>
                                                        <option value="Delivered" <?php if ($row['updates'] === 'Delivered') echo 'selected'; ?>>Delivered</option>
                                                    </select>
                                                    <button type="submit" name="action" value="update" class="btnu btn-primary">Update</button>
                                                    <button type="submit" name="action" value="delete" class="btnd btn-danger" onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btnc btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of moda; -->
                <?php } ?>
            </tbody>
        </table>
        </ul>
    </div>
</div>

    <?php } else {
        echo "<div class='alert alert-warning'>No Order</div>";
    } ?>
</div>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#orderTable').DataTable();
        });
    </script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>