<?php
require 'config.php';
if(!empty($_SESSION["user_id"])){
    $id = $_SESSION["user_id"];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
}
else{
    header("Location: login.php");
}


// cancel order customer
if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: login.php");
}

// cancel order customer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted using POST method
    if (!empty($_POST['id']) && !empty($_POST['cancellation_reason'])) {
        // Sanitize input data
        $order_id = mysqli_real_escape_string($conn, $_POST['id']);
        $cancellation_reason = mysqli_real_escape_string($conn, $_POST['cancellation_reason']);
        $cancellation_message = mysqli_real_escape_string($conn, $_POST['cancellation_message']);

        
        // Update order status and add cancellation information to the database
        $update_query = "UPDATE `order` SET 
                            `updates` = 'Cancelled', 
                            `cancellation_reason` = '$cancellation_reason',
                            `cancellation_message` = '$cancellation_message',
                            `cancellation_date` = NOW()
                        WHERE `id` = $order_id";

        if (mysqli_query($conn, $update_query)) {
            echo '<div class="success-message">Order cancelled successfully.</div>';
        } else {
            echo "Error updating order: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid input data.";
    }
} else {
    // Handle other cases if needed
}


?>

<style>
  /* Common styles */
  .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .order {
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 30%;
        }

        .order p {
            margin: 2px;
        }

        .update {
            text-align: center;
            border: 2px solid black;
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
        margin-top: 15%;
    }

        h2 {
            text-align: center;
        }

        .totalpro{
        width: 98%;
        display: block; /* Display as a block-level element */
        padding: 10px; /* Add padding as needed for spacing */
        margin: 5px; /* Add margin for spacing between columns */
        border: 1px solid #333; /* Add a border for separation */
        text-align: center; /* Center the content horizontally */
        /* Add other styles as desired */
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .order {
                width: 100%;
            }
        }

        .success-message {
    background-color: #28a745; /* Bootstrap success alert color */
    color: #fff;
    padding: 10px;
    margin: 10px auto; /* Center the element horizontally with top and bottom margins */
    border-radius: 5px;
    text-align: center;
    width: 80%;
    }

    .container {
            background-color: #f8f9fa; /* Replace with your desired color code */
            padding: 20px; /* Optional: Add padding to the container */
            border-radius: 20px;
        }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<body>

<?php include 'navbar.php'; ?>

<!-- Customer Receipt -->
<?php
// Assuming you have already started the session and included your database connection

$user_id = $_SESSION['user_id'];

// Fetch orders
$select_orders = mysqli_query($conn, "SELECT * FROM `order` WHERE `user_id` = $user_id ORDER BY `order_date` DESC");

if (mysqli_num_rows($select_orders) > 0) {
    ?>
    <div class="container mt-4">
        <table id="orderlistTable" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order Status</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($order_row = mysqli_fetch_assoc($select_orders)) {
                    ?>
                    <tr>
                        <td><?php echo $order_row['updates']; ?></td>
                        <td><?php echo $order_row['order_date']; ?></td>
                        <td><?php echo $order_row['payment']; ?></td>
                        <td class="product-list">
                        <?php
                            $products = $order_row['total_products'];
                            $productArray = explode(': ', $products);
                            $maxProductsToShow = 5;

                            // Display a truncated product list with a "See More" link
                            echo '<div class="truncated-products">';
                            $truncatedProducts = array_slice($productArray, 0, $maxProductsToShow);
                            echo implode('<br>', array_map('strip_tags', $truncatedProducts)) . '</div>';

                            if (count($productArray) > $maxProductsToShow) {
                                // Display "See More" or "Show Less" link
                                echo '<br><a href="#" class="see-more-link">See All...</a>';

                                // Store the full product list as a data attribute
                                echo '<div class="full-products" style="display:none;">' . implode('<br>', array_map('strip_tags', $productArray)) . '</div>';
                            }
                            ?>

                        </td>
                        <td>₱ <?php echo number_format($order_row['total_price'], 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-danger <?php echo ($order_row['updates'] == 'Delivered' || $order_row['updates'] == 'Cancelled' || $order_row['updates'] == 'To Ship') ? 'disabled' : ''; ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelModal<?php echo $order_row['id']; ?>" 
                                    data-order-id="" 
                                    data-order-date="<?php echo $order_row['order_date']; ?>">
                                Cancel
                            </button>
                        </td>
                    </tr>

                    <!-- Modal cancel order-->
                    <div class="modal fade" id="cancelModal<?php echo $order_row['id']; ?>" data-order-date="<?php echo $order_row['order_date']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cancellation Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="myorder.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $order_row['id']; ?>">
                                        <label for="cancellation_reason">Reason for Cancellation:</label>
                                        <select class="form-select" name="cancellation_reason" id="cancellation_reason" required>
                                            <option value="Item not available">Duplicate Order</option>
                                            <option value="Changed my mind">Changed my mind</option>
                                            <option value="Shipping issues">Shipping issues</option>
                                            <option value="Product quality issues">Want to Change payment method</option>
                                            <option value="Financial reasons">Financial reasons</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="form-group" id="other_reason_container" style="display: none;">
                                            <label for="other_reason">Other Reason:</label>
                                            <input type="text" class="form-control" name="other_reason" id="other_reason">
                                        </div>
                                        <br>
                                        <label for="cancellation_message" style="display: none;">Cancellation Message:</label>
                                        <textarea class="form-control" name="cancellation_message" style="display: none;"><?php echo "Cancelled Order"; ?></textarea>
                                        <br>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<div class='empty'>No Orders</div>";
}
?>







<!-- Inside the while loop where you display orders -->
<!-- <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="cancel_order.php" method="post">
                    <input type="hidden" name="order_id" value="<?php echo $order_row['order_id']; ?>">
                    <label for="cancellation_reason">Reason for Cancellation:</label>
                    <textarea class="form-control" name="cancellation_reason" required></textarea>
                    <br>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </form>
            </div>
        </div>
    </div>
</div> -->




</body>


<script>

// other reason for cancellation

    // Script to show/hide the "Other" reason input based on user selection
    document.getElementById('cancellation_reason').addEventListener('change', function () {
        var otherReasonContainer = document.getElementById('other_reason_container');
        otherReasonContainer.style.display = this.value === 'Other' ? 'block' : 'none';
    });

// end of other reason for cancellation


    // Add this script to your HTML or in a separate JavaScript file
document.addEventListener('DOMContentLoaded', function () {
    var cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));

    // Attach click event to each cancel button
    var cancelButtons = document.querySelectorAll('.btn-danger[data-bs-toggle="modal"]');
    cancelButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var orderId = button.getAttribute('data-order-id');
            var orderDate = button.getAttribute('data-order-date');

            // Populate modal form fields with order details
            document.querySelector('#cancelModal [name="order_id"]').value = orderId;
            document.querySelector('#cancelModal [name="cancellation_reason"]').value = '';

            // Update modal title with order information
            document.querySelector('#cancelModal .modal-title').innerHTML = 'Cancellation Order - ID: ' + orderId + ', Date: ' + orderDate;

            // Show the modal
            cancelModal.show();
        });
    });
});


// automatic close order cancel

    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Select the success message element
        var successMessage = document.querySelector('.success-message');

        // Check if the success message element exists
        if (successMessage) {
            // Automatically close the success message after 3 seconds
            setTimeout(function () {
                successMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });
    

        // Initialize DataTable
        $(document).ready(function() {
    $('#orderlistTable').DataTable();
});



// show more and show less button 
$(document).ready(function () {
    // Function to toggle between truncated and full product lists
    function toggleProducts($container) {
        var $truncatedProducts = $container.find('.truncated-products');
        var $fullProducts = $container.find('.full-products');
        var $seeMoreLink = $container.find('.see-more-link');

        $truncatedProducts.toggle();
        $fullProducts.toggle();

        // Check if truncated products are visible
        if ($truncatedProducts.is(':visible')) {
            $seeMoreLink.text('See More...');
        } else {
            // If truncated products are not visible, show all products
            $seeMoreLink.text('Show Less');
        }
    }

    // Click event for "See More" or "Show Less" link
    $('.product-list').on('click', '.see-more-link', function (e) {
        e.preventDefault();
        var $productListContainer = $(this).closest('.product-list');
        toggleProducts($productListContainer);
    });
});

// end




</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
