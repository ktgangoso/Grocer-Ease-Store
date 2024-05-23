   <?php
   session_start(); 

   require 'config.php';
   @include 'config.php';

   // Check if the user is logged in
   if (!isset($_SESSION['user_id'])) {
      header('Location: user/login.php');
      exit();
   }

   // Retrieve user information
// Retrieve user information
$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update_update_btn'])) {
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];

    // Check if the input is a valid 11-digit number
    if (strlen($update_value) <= 11 && is_numeric($update_value)) {
        $update_value = (int)$update_value; // Convert to integer for safety

        // Check if the entered quantity is greater than 0
        if ($update_value > 0) {
            // Retrieve product information based on the cart item being updated
            $select_cart_item = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$update_id'");
            $fetch_cart_item = mysqli_fetch_assoc($select_cart_item);

            // Check if the cart item exists
            if ($fetch_cart_item) {
                $select_product = mysqli_query($conn, "SELECT * FROM `product` WHERE name = '{$fetch_cart_item['name']}'");
                $fetch_product = mysqli_fetch_assoc($select_product);

                // Check if the entered quantity is within the available stock
                if ($update_value <= $fetch_product['stock']) {
                    // Update the quantity in the cart
                    $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
                    if ($update_quantity_query) {
                        header('location:cart.php');
                    } else {
                        echo '<div class="error-message" id="errorMessage">Failed to update quantity.</div>';
                    }
                } else {
                    // Display a message when the entered quantity exceeds the available stock
                    echo '<div class="error-message" id="errorMessage">Entered quantity exceeds the available stock (Current Stock: ' . $fetch_product['stock'] . ').</div>';
                }
            } else {
                echo '<div class="error-message" id="errorMessage">Cart item not found.</div>';
            }
        } else {
            // Display a message when the entered quantity is not greater than 0
            echo '<div class="error-message" id="errorMessage">Please enter a quantity greater than 0.</div>';
        }
    } else {
        // Display a message when the quantity is not a valid 11-digit number
        echo '<div class="error-message" id="errorMessage">Please enter a valid 11-digit quantity.</div>';
    }

    echo '<script>
           setTimeout(function() {
               document.getElementById("errorMessage").style.display = "none";
           }, 4000); // Hide the error message after 4 seconds
       </script>';
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart`");
    header('location:cart.php');
}


   // cart stock available

  
   ?>




<style>
         
      body{
      height: 100vh;
      background-size: cover;
         background-position: center;
         background-color: white;
         background-repeat: no-repeat;
      align-items: center;
         justify-content: center;
      
   }

      /* error message if not 11 digit number */
      .error-message {
            background-color: #ff5555;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 10px 0;
      }
      


</style>
   

   <?php include 'navbar.php'; ?>

      

   <section class="shopping-cart">


      <table>

         <thead>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Total Price</th>
            <th>Quantity</th>
            <th>Action</th>
         </thead>

         <tbody>

            <?php 

            $user_id = $_SESSION['user_id']; // Assuming you store user ID in a session variable after login
            $select_cart = mysqli_query($conn, "SELECT c.*, p.stock FROM `cart` c INNER JOIN `product` p ON c.name = p.name WHERE c.`user_id` = $user_id");


            $grand_total = 0;
            if(mysqli_num_rows($select_cart) > 0){
               while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            ?>

            <tr>
               <td data-cell="Image"><img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" height="100px" alt=""></td>
               <td data-cell="Name"><?php echo $fetch_cart['name']; ?></td>
               <td data-cell="Price">₱<?php echo number_format($fetch_cart['price'], 2); ?></td>
               <td data-cell="Stock"> <?php echo $fetch_cart['stock']; ?> </td>
               <?php
               $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
               ?>
               <td data-cell="Total Price">₱<?= number_format($sub_total, 2); ?></td>
               <td data-cell="Quantity">
               <form action="" method="post">
                  <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
                  <input type="number" name="update_quantity" id="update_quantity" min="1" maxlength="10" style="text-align: center;" value="<?php echo $fetch_cart['quantity']; ?>">
                  <input type="submit" value="Update" name="update_update_btn">
               </form>

               </td>
               <td data-cell="Action">
                  <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" style="padding: .6rem;" onclick="return confirm('Remove item from cart?')" class="btn btn-danger">
                     <i class="fas fa-trash"></i> Remove
                  </a>
               </td>
            </tr>
            <?php
            $grand_total += $sub_total;  
               };
            };
            ?>

               <tr class="table-bottom">
                  <td><a href="store.php" style="padding: .8rem;" class="btn btn-outline-primary">Continue Shopping</a></td>
                  <td colspan="3">Total </td>
                  <td>₱<?php echo number_format($grand_total, 2); ?></td>
                  <td>
                     <a href="checkout.php" style="padding: .8rem;" class="btn btn-primary <?= ($grand_total > 1) ? '' : 'disabled'; ?>">
                        <i class="bi bi-box-arrow-in-right"></i> Proceed to Checkout
                     </a>
                  </td>
                  <td>
                     <a href="cart.php?delete_all" style="padding: .8rem;" onclick="return confirm('Are you sure you want to delete all?');" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete All
                     </a>
                  </td>
               </tr>

            <!-- <tr class="table-bottom">
               <td><a href="store.php" class="options" style="margin-top: 0;">Continue Shopping</a></td>
               <td colspan="3">Total </td>
               <td>₱<?php echo $grand_total; ?></td>
               <td><a href="checkout.php" class="checkout-btn" style="border: none;" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>"><i class="bi bi-box-arrow-in-right"></i> proceed to check out</a></td>
               <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="deletes"> <i class="fas fa-trash"></i>Delete All</a></td>
            </tr> -->

         </tbody>

      </table>

      <div >

      </div>

      <br>

   </section>






      
   <script src="script.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
