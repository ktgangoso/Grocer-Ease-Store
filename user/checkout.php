<?php

require 'config.php';
if(!empty($_SESSION["user_id"])){
    $user_id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);

    
    // Retrieve user information
    $name = $row['fname'] . ' ' . $row['lname'];
    $number = $row['number'];
    $email = $row['email'];
    $address = $row['address'];
}

else{
    header("Location: user/login.php");

}


if (isset($_POST['order_btn'])) {
    $user_id = $_SESSION['user_id']; // Assuming you store user ID in a session variable after login
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $payment = $_POST['payment'];
    $address = $_POST['address'];
    
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE `user_id` = $user_id");
    $price_total = 0;
    $product_details = array(); // Create an array to store product details
    $message = array(); // Create an array to store error messages

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name = $product_item['name'];
            $product_quantity = $product_item['quantity'];
            $product_price_per_unit = $product_item['price'];
            $product_price = $product_quantity * $product_price_per_unit;

            // Product details for the receipt
            $product_details[] = array(
                'name' => $product_name,
                'quantity' => $product_quantity,
                'price_per_unit' => $product_price_per_unit,
                'total_price' => $product_price
            );

            $price_total += $product_price;
        }
    }

    // Check if $number is within the 11-digit limit
    if (is_numeric($number) && strlen($number) === 11) {
        $total_products = ""; // Initialize the total products string

        foreach ($product_details as $product) {
            // Format product details for the receipt
            $product_line = "{{$product['quantity']}x {$product['name']}} Total = ₱{$product['total_price']} - ₱{$product['price_per_unit']} each: ";
            $total_products .= $product_line . "<br>";
        }

        $base_total = $_POST['total_price'] ?? 0; // Initialize $base_total to $_POST['total_price'] or 0 if not set

// Check if shippingLocation is set in the POST data
if (isset($_POST['shippingLocation'])) {
    // Get the selected shipping location from the POST data
    $selected_location = $_POST['shippingLocation'];

    // Define the $shipping_fee_map array (replace this with your actual fee values)
    $shipping_fee_map = array(
        'Taguig City' => 30,
        'Paranaque City' => 50,
        'Muntinlupa City' => 70,
        'Makati City' => 70,
        'Pasig City' => 70,
        'Las Pinas City' => 70,
        'Manila City' => 100,
        'Quezon City' => 150,
    );

    // Get the shipping fee from the $shipping_fee_map array
    $shipping_fee = $shipping_fee_map[$selected_location];

    // Calculate the new total price including the shipping fee
    $total_price = $base_total + $shipping_fee;
} else {
    // Handle the case where shippingLocation is not set
    // You might want to set a default shipping fee or display an error message
    $total_price = $base_total;
}

        $order_date = date("Y-m-d H:i:s"); // Get the current date and time

        $stmt = mysqli_prepare($conn, "INSERT INTO `order` (user_id, name, number, email, payment, address, total_products, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "dssssssds", $user_id, $name, $number, $email, $payment, $address, $total_products, $total_price, $order_date);

            if (mysqli_stmt_execute($stmt)) {
                // Order was placed successfully, now clear the cart
                $clear_cart_query = mysqli_query($conn, "DELETE FROM `cart` WHERE `user_id` = $user_id");

                if ($clear_cart_query) {
                    // Cart is cleared

                    // Reduce stock for each product in the order
                    foreach ($product_details as $product) {
                        $product_name = $product['name'];
                        $product_quantity = $product['quantity'];

                        // Retrieve the current stock of the product
                        $select_product = mysqli_query($conn, "SELECT stock FROM `product` WHERE name = '$product_name'");
                        $fetch_product = mysqli_fetch_assoc($select_product);
                        $current_stock = $fetch_product['stock'];

                        // Calculate the new stock after reducing the quantity
                        $new_stock = $current_stock - $product_quantity;

                        // Update the stock in the product table
                        $update_stock_query = mysqli_query($conn, "UPDATE `product` SET stock = '$new_stock' WHERE name = '$product_name'");

                        if ($update_stock_query) {
                            // Check if the updated stock is not equal to the expected stock
                            if ($new_stock != $current_stock - $product_quantity) {
                                // Display an error message
                                $message[] = 'Failed to update stock for product: ' . $product_name . '. Please enter an available stock.';
                            }
                        } else {
                            // Failed to update stock
                            $message[] = 'Failed to update stock for product: ' . $product_name;
                        }
                    }

                    echo "
                    <div class='order-message-container'>
                        <div class='message-container'>
                            <h3>Thank You for Shopping!</h3>
                            <div class='order-detail'>
                                <span>" . $total_products . "</span> <br>
                                <span class='total'> total:  ₱" . $total_price . "  </span>
                            </div>
                        </div>
                    </div>
                    <script>
                    // Add JavaScript to close the order message container after 3 seconds
                    setTimeout(function() {
                        var orderMessageContainer = document.querySelector('.order-message-container');
                        if (orderMessageContainer) {
                            orderMessageContainer.style.display = 'none';
                        }
                    }, 5000); // 3000 milliseconds (3 seconds)
                    </script>
                    ";
                } else {
                    // Failed to clear the cart
                    $message[] = 'Failed to clear the cart.';
                }

                // Check if there are any error messages
                if (!empty($message)) {
                    // Display the error messages
                    foreach ($message as $error) {
                        echo '<div class="error-message">' . $error . '</div>';
                    }
                }
            } else {
                // Failed to insert the order
                $message[] = 'Failed to place the order.';
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            // Error in preparing the statement
            $message[] = 'Error in preparing the statement.';
        }

    } else {
        // Handle the case where the number is not within the 11-digit limit
        $message[] = 'Please enter a valid 11-digit number.';
    }
}


?>
<style>
    /* error message if not 11 digit number
    .error-message {
            background-color: #ff5555;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 10px 0;
        } */

    .quantity, .name, .price {
        padding: 1rem;
    }
    .product-info{
        text-transform: capitalize;
    }

    /* message */
    .order-message-container {
        background-color: #f5f5f5;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
    }

    .message-container {
        background-color: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    h3 {
        font-size: 24px;
        color: #333;
    }

    .order-detail {
        margin: 20px 0;
        font-size: 18px;
    }

    .total {
        color: #ff5733;
    }

    .customer-details {
        font-size: 16px;
        text-align: left;
    }

    p {
        margin: 5px 0;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }

    .btn:hover {
        background-color: #555;
    }


    .add-product-message span {
        font-size: 14px;
    }

    /* pls add to cart button */
    .pls{
    text-decoration: none;
    background-color: #007bff;
    color: #000;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    margin-top: 2rem;
    margin-left: 10px;
    height: 3rem;
    }
    .pls:hover{
    background-color: #999;
    color: #ffffff;
    }
    /* back button */
    .backbtn {
    text-decoration: none;
    background-color: #007bff;
    color: #000;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    margin-top: 2rem;
    margin-left: 10px;
    height: 3rem;
    }

    .backbtn:hover {
    background-color: #999;
    color: #ffffff;
    }

</style>

<?php include 'navbar.php';?>


<script>
    // Wait for the document to load
    document.addEventListener("DOMContentLoaded", function () {
        // Find the message container
        const messageContainer = document.getElementById("message-container");

        // Check if the container is found and not empty
        if (messageContainer && messageContainer.innerHTML.trim() !== "") {
            // Set a timeout to hide the message container after 3 seconds
            setTimeout(function () {
                messageContainer.style.display = "none";
            }, 3000); // 3000 milliseconds (3 seconds)
        }
    });
    </script>

<div id="message-container" class="message">
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message"><span>' . $message . '</span>  </div>';
        }
    };
    ?>
</div>


<div class="containercheck">
   <section class="checkout-form">
      <h1 class="heading">Complete Your Order</h1>
      <form class="checkout" action="" method="post">
         <div class="display-order">
            <?php
            // Assuming $user_id is available, you can use it in your query
        //  $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `user_id` = $user_id");
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `user_id` = $id");

            $total = 0;
            $grand_total = 0;
            if (mysqli_num_rows($select_cart) > 0) {
               while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                  $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                  $grand_total = $total += $total_price;
            ?>
                  <div class="product-info">
                     <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" height="50px" width="50px">
                     <span class="quantity"><?php echo $fetch_cart['quantity']; ?>x</span>
                     <span class="price">₱ <?php echo number_format($fetch_cart['price'], 2); ?></span>
                     <span class="name"><?php echo $fetch_cart['name']; ?> </span>
                  </div>
            <?php
               }
            } else {
               echo "<div class='display-order'><span>Your Cart is Empty!</span></div>";
            }
            ?>
            <span class="grand-total"> Total : ₱ <?= number_format($grand_total, 2); ?></span><br>
            <div class="inputBox" id="shippingFeeContainer"> <?= number_format($grand_total, 2); ?></div>



         </div>

         <div class="flex">
            <div class="inputBox">
               <span>Name</span>
               <input type="text" placeholder="Enter your Name" name="name" value="<?= $name ?>" required>
            </div>

            <div class="inputBox">
               <span>Number</span>
               <input type="number" placeholder="Enter your Number" name="number" maxlength="11" value="<?= $number ?>"  required>
            </div>

            <div class="inputBox">
               <span>Email</span>
               <input type="email" placeholder="Enter your Email" name="email" value="<?= $email ?>" required>
            </div>

            <div class="inputBox">
               <span>Payment Method</span>
               <select name="payment" id="paymentMethod">
                  <option value="Cash on Delivery" selected>Cash On Delivery</option>
                  <option value="Pick Up">Pick Up</option>
               </select>
            </div>

            <div class="inputBox">
               <span>Address</span>
               <input type="text" placeholder="Enter your Address" name="address" value="<?= $address ?>" required>
            </div>

            <!-- Add this hidden input field inside your form -->
            <input type="hidden" name="total_price" value="<?= $grand_total ?>">


            <?php
            if (mysqli_num_rows($select_cart) == 0) {
                echo "<a href='store.php' class='pls'><div><span>Please Add Product</span></div></a>";
            } else {
                // Display the "Place Order" button only if user_id is available
                echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
                echo '<input type="submit" value="Place Order" name="order_btn" class="btnorder">';
            }
            ?>
            <a href="cart.php" class="backbtn">Back To Cart</a>
         </div>
      </form>
            <div class="inputBox" id="shippingFeeContent">
               <span>Shipping Fee Location</span>
               <select id="shippingLocation" name="shippingLocation">
                    <option value="Taguig City" data-fee="30" selected>Taguig City to Taguig City: ₱30 Shipping fee</option>
                    <option value="Paranaque City" data-fee="50">Taguig City to Paranaque City: ₱50 Shipping fee </option>
                    <option value="Muntinlupa City" data-fee="70">Taguig City to Muntinlupa City: ₱70 Shipping fee </option>
                    <option value="Makati City" data-fee="70">Taguig City to Makati City: ₱70 Shipping fee </option>
                    <option value="Pasig City" data-fee="70">Taguig City to Pasig City: ₱70 Shipping fee </option>
                    <option value="Las Pinas City" data-fee="70">Taguig City to Las Pinas City: ₱70 Shipping fee </option>
                    <option value="Manila City" data-fee="100">Taguig City to Manila City: ₱100 Shipping fee </option>
                    <option value="Quezon City" data-fee="150">Taguig City to Quezon City: ₱150 Shipping fee </option>
                </select>

            </div>
   </section>
</div>


<script>



</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
// js for place order, 3 second will show the receipt
    // Automatically close the order message after 3 seconds
    setTimeout(function () {
    var orderMessageContainer = document.getElementById('order-message-container');
    if (orderMessageContainer) {
        orderMessageContainer.style.display = 'none';
    }
}, 3000); // 3000 milliseconds = 3 seconds
// end

// cash on delivery showing shipping fee location
    $(document).ready(function () {
        function updateTotalPrice() {
            var selectedPayment = $("#paymentMethod").val();
            var selectedLocation = $("#shippingLocation").val();

            // Assuming that the PHP code <?= $grand_total ?> outputs the correct value
            var baseTotal = <?= $grand_total ?>;

            if (selectedPayment === "Cash on Delivery") {
                // Get the shipping fee from the selected option's data attribute
                var shippingFee = $("#shippingLocation option:selected").data("fee");

                // Display the shipping fee amount
                $("#shippingFeeContainer").html("Shipping Fee: ₱" + shippingFee);

                // Calculate the new total price including the shipping fee
                var totalPrice = baseTotal + parseInt(shippingFee);

                // Display the updated total price, including the shipping fee
                $(".grand-total").html("Total: ₱" + totalPrice.toFixed(2));

                // Update the hidden input field for total_price in the form
                $("input[name='total_price']").val(totalPrice.toFixed(2));

                // Show the shipping fee container
                $("#shippingFeeContainer").show();

                // Show the shipping fee content
                $("#shippingFeeContent").show();
            } else {
                // Reset the shipping fee display
                $("#shippingFeeContainer").html("");

                // Reset the total price to the original grand total without shipping fee
                $(".grand-total").html("Total: ₱<?= number_format($grand_total, 2) ?>");

                // Update the hidden input field for total_price in the form
                $("input[name='total_price']").val(baseTotal.toFixed(2));

                // Hide the shipping fee container
                $("#shippingFeeContainer").hide();

                // Hide the shipping fee content
                $("#shippingFeeContent").hide();
            }
        }

        // Bind the function to the change event of the payment method and shipping location selects
        $("#paymentMethod, #shippingLocation").change(function () {
            updateTotalPrice();
        });

        // Trigger the change event to check the initial values on page load
        updateTotalPrice();
    });
// end


var baseTotal = <?= $grand_total ?>;

if (selectedPayment === "Cash on Delivery") {
    // Get the shipping fee from the selected option's data attribute
    var shippingFee = $("#shippingLocation option:selected").data("fee");

    // Calculate the new total price including the shipping fee
    var totalPrice = baseTotal + parseInt(shippingFee);

    // Display the updated total price, including the shipping fee
    $(".grand-total").html("Total: ₱" + totalPrice.toFixed(2));
} else {
    // Reset the total price to the original grand total without shipping fee
    $(".grand-total").html("Total: ₱<?= number_format($grand_total, 2) ?>");
}



</script>


<script src="scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
