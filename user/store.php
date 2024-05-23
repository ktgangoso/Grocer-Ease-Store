<?php

require 'config.php';

if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: login.php");
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity']; // Get the quantity from the form

    // Assuming you have the user_id stored in the session
    $user_id = $_SESSION['user_id'];

    // Check if the product quantity is greater than 0 and is a valid 11-digit number
    if (strlen($product_quantity) <= 11 && is_numeric($product_quantity)) {
        $product_quantity = (int) $product_quantity; // Convert to integer for safety

        if ($product_quantity > 0) {
            $select_product = mysqli_query($conn, "SELECT * FROM `product` WHERE name = '$product_name'");
            $fetch_product = mysqli_fetch_assoc($select_product);

            // Check if the entered quantity is less than or equal to the available stock
            if ($product_quantity <= $fetch_product['stock']) {
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND name = '$product_name'");

                if (mysqli_num_rows($select_cart) > 0) {
                    // Product is already in the cart for this user, update its quantity
                    $cart_item = mysqli_fetch_assoc($select_cart);
                    $new_quantity = $cart_item['quantity'] + $product_quantity;
                    $update_quantity = mysqli_query($conn, "UPDATE `cart` SET quantity = $new_quantity WHERE user_id = '$user_id' AND name = '$product_name'");
                    if ($update_quantity) {
                        $message[] = 'Product Added to Cart Successfully';
                    } else {
                        $message[] = 'Failed to Update Product Quantity in Cart';
                    }
                } else {
                    // Product is not in the cart for this user, insert it
                    $insert_product = mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')");
                    if ($insert_product) {
                        $message[] = 'Product Added to Cart Successfully';
                    } else {
                        $message[] = 'Failed to Add Product to Cart';
                    }
                }
            } else {
                // Display a message when the product quantity exceeds the available stock
                $message[] = 'Entered quantity exceeds the available stock.';
            }
        } else {
            // Display a message when the product quantity is 0
            $message[] = 'Please Enter a Quantity Greater than 0.';
        }
    } else {
        // Display a message when the quantity is not an 11-digit number
        $message[] = 'Please Enter a Valid 11-digit Quantity.';
    }
}





?>


<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<style>
     /* Style for the input element with type="number" */
  input[type="number"] {
    width: 50px; /* Set the width to your desired size */
    padding: 5px; /* Add padding for spacing */
    font-size: 16px; /* Set the font size */
    border: 1px solid #ccc; /* Add a border */
    border-radius: 5px; /* Add rounded corners */
    text-align: center;
  }

  /* Style for the input when it's in focus (clicked or selected) */
  input[type="number"]:focus {
    outline: none; /* Remove the default focus outline */
    border-color: #007bff; /* Change the border color on focus */
    box-shadow: 0 0 5px #007bff; /* Add a subtle box shadow on focus */
  }

  .fixed-dimensions {
        max-width: 900px; /* Set the maximum width */
        width: 900px; /* Make it responsive */
        margin: -3rem auto; /* Center the form horizontally */
        padding: 10px; /* Add some padding for better aesthetics */
    }

    .input-group {
        display: flex;
        gap: 10px;
    }

    .fixed-dimension {
        /* max-width: 100rem;  */
        width: 80rem; 
    }



    /* categories button */

    .btn-group {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
    max-width: auto; /* Set a maximum width for the container */
    margin: 0 auto; /* Center the container horizontally on the page */
    }

    .btn-group a {
        flex: 1;
        max-width: auto; /* Limit the maximum width of each button */
        font-size: 15px;
        padding: .5rem;
    }



    /* Rest of the styling remains the same */
    .btn {
        display: inline-block;
        padding: 10px 15px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 5px;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn:hover {
        filter: brightness(90%);
    }

    
    /* add to cart button css */

    .CartBtn {
    width: 140px;
    height: 40px;
    border-radius: 12px;
    border: none;
    background-color: #3484fa;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition-duration: .5s;
    overflow: hidden;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.103);
    position: relative;
    margin-left: .5rem;
    }

    .IconContainer {
    position: absolute;
    left: -50px;
    width: 30px;
    height: 30px;
    background-color: transparent;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 2;
    transition-duration: .5s;
    }

    .icon {
    border-radius: 1px;
    }

    .text {
    height: 100%;
    margin-top: 15px;
    width: fit-content;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgb(17, 17, 17);
    z-index: 1;
    transition-duration: .5s;
    font-size: 1.04em;
    font-weight: 600;
    }

    .CartBtn:hover .IconContainer {
    transform: translateX(58px);
    border-radius: 40px;
    transition-duration: .5s;
    }

    .CartBtn:hover .text {
    transform: translate(10px,0px);
    transition-duration: .5s;
    }

    .CartBtn:active {
    transform: scale(0.95);
    transition-duration: .5s;
    }

    /* modal add to cart style */
    .modal .modal-dialog{
    margin-top: 5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    }
    .modal-content {
    display: flex;
    justify-content: center;
    align-items: center;
    text-transform: capitalize;
    height: 30rem;
    width: 25rem;
    }
    .modal-content .modal-body h3{
    display: flex;
    justify-content: center;
    align-items: center;
    text-transform: capitalize;
    }
    .modal-body .price{
    text-transform: capitalize;
    text-align: center;
    margin-top: 2rem;
    margin-bottom: 1rem;
    }
    .modal-body img{
    margin-left: 3rem;
    height: 100px;
    width: 100px;
    }

    /* add to cart css */
            .boxs h3 {
        margin-top: 10px;
        font-size: 1.2rem;
        font-weight: 600;
        }

        .price {
        margin-top: 5px;
        font-size: 1.1rem;
        color: black;
        font-weight: 600;
        }

        .btnadd {
        display: block;
        margin-top: 15px;
        padding: 8px 10px;
        background-color: #3498db;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        font-size: 20px;
        text-transform: capitalize;
        }

        .btnadd:hover {
        background-color: #2980b9;
        transform: scale(1.03);
        }

        .boxs:hover {
        transform: scale(1.03);
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .search-container {
        display: flex;
        }
        

        /* search responsive */

        /* Adjustments for smaller screens */
        @media (max-width: 576px) {
            .fixed-dimension {
                width: 100%; /* Full width for smaller screens */
                position: static; /* Remove fixed position on smaller screens */
            }
        }


      /* Responsive styles add to cart*/
      @media (max-width: 600px) {
            /*.CartBtn {*/
            /*height: 20px;*/
            /*width: 70px;  */
            /*display: flex;*/
            /*justify-content: center; */
            /*align-items: center;*/
            /*    background-color: #3484fa;*/
            /*    margin: auto;*/
            /*    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.103);*/
            /*    font-size: 10px;*/
            /*}*/

            .boxs h3 {
            font-size: 8px;
            }

            /* add to cart  */
            .boxs-container{
            display: flex;
            flex-wrap: wrap;

            }

            .item-row .boxs {
            /* Adjust the styles for smaller screens */
            height: auto; /* You can adjust this based on your design */
            width: 100%; /* Make it full width for smaller screens */
            margin: 5px 5px 2px 10px; /* Adjust margin as needed */
            }

            .item-row h3 {
            font-size: 15px;
            }

            .item-row img {
            height: 50px;
            width: 50px;
            } 

            .category-buttons{
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .category-buttons .btn{
                width: 5rem;
                height: auto;
                font-size: 8px;
            }

            /* seach responsive */
            

            /* search responsive */

            .form-inline {
            width: 100%;
            margin-left: 1rem;
            margin-top: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            }

            .form-inline button{
            height: 40px;
            margin-top: 1rem;
            margin-left: 1rem;
            color: #ffffff;
            border: 1px solid black;
            }


        }

</style>
</head>

<?php include 'navbar.php'; ?>


<div id="message-container" class="message">
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message"><span>' . $message . '</span>  </div>';
        }
    };
    ?>
</div>



    
    <div class="container">

<section class="products">

<form class="form-inline " method="GET">
    <div class="container mt-3">
        <!--<div class="input-group">-->
            <input type="search" class="form-control" aria-label="Search" name="search" placeholder="Search Product" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>">
            <div class="input-group-append">
                <button class="btn btn-outline-success m-0" type="submit" name="submit"> Search</button>
            </div>
        <!--</div>-->
    </div>
</form>


      <div class="boxsearch">
      <?php
      // Store 1
         if(isset($_GET['search']) && !empty($_GET['search']))
         {
            $connection = mysqli_connect("localhost", "id22178904_register", "P@55w0rd123", "id22178904_grocereaststore");
            $filterdata = $_GET['search'];
            $filterdata = "SELECT * FROM product WHERE CONCAT(name, categories) LIKE '%$filterdata%'";
            $filterdata_run = mysqli_query($connection, $filterdata);

            if(mysqli_num_rows($filterdata_run) > 0)
            {
               foreach($filterdata_run as $row)
               {
                  ?>
                     <div class="item-row">
                        <form action="" method="post">
                           <div class="boxs">
                                 <img src="uploaded_img/<?php echo $row['image']; ?>" alt="" height="150px" width="150px">
                                 <h3><?php echo $row['name']; ?></h3>
                                 <div class="price">₱<?php echo number_format($row['price'], 2); ?></div>
                                 <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                 <input type="hidden" name="product_price" value="<?php echo number_format($row['price'], 2); ?>">
                                 <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                                 
            <!-- Button trigger modal -->
            <!-- <button type="button" class="btnadd btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']; ?>">
            Add to Cart
            </button> -->
                     

            <button class="CartBtn" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['product_id']; ?>">
            <span class="IconContainer"> 
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" fill="rgb(17, 17, 17)" class="cart"><path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path></svg>
            </span>
            <p class="text">Add to Cart</p>
            </button> 

        </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal<?php echo $row['product_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Product Details</h1>
                     <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                     </div>

                     <div class="modal-body">
                           <img src="uploaded_img/<?php echo $row['image']; ?>" alt="">
                           <h3><?php echo $row['name']; ?></h3>
                           <div class="price">₱<?php echo number_format($row['price'], 2); ?></div>
                           <div class="stock">Stock: <?php echo $row['stock']; ?></div>
                           <!-- <h3>avail</h3> -->


                           <form method="post" action="your_php_script.php">
                              <!-- Add an input field for quantity -->
                              <input type="number" name="product_quantity" min="1" value="1">
                           </form>

                           <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                           <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                           <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                     </div>

                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- <button type="submit" class="btn btn-primary" value="Add to Cart" name="add_to_cart" <?php echo $row['id']?>>Add to Cart</button> -->

                            <button class="CartBtn" type="submit" value="Add to Cart" name="add_to_cart" <?php echo $row['product_id']?>>
                            <span class="IconContainer"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" fill="rgb(17, 17, 17)" class="cart"><path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path></svg>
                            </span>
                            <p class="text">Add to Cart</p>
                            </button>
                    </div>
                     
                  </div>
               </div>
            </div>

                        </form>
                     </div>
                  <?php
               }
            }
            else
            {
               ?>
               <h1>No Product Found</h1>
               <?php
            }
         }


      ?>
</div>
      
   <!-- <h1 class="heading">Latest Products</h1> -->
   
  
   
<div class="container">

    <section class="products">

    <?php
        // Get the selected category filter
        $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

        // Fetch all unique categories from the database
        $category_query = mysqli_query($conn, "SELECT DISTINCT categories FROM `product`");
        $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC);
        ?>

        <!-- <form method="GET" class="mb-3 fixed-dimensions">
            <div class="input-group">
                <select class="form-select" id="category" name="category">
                    <option value="" selected>All Categories</option>
                    <?php
                    // Display each category as an option in the dropdown
                    foreach ($categories as $cat) {
                        $selected = ($category_filter == $cat['categories']) ? 'selected' : '';
                        echo "<option value='{$cat['categories']}' {$selected}>{$cat['categories']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form> -->

        
        <div class="container mt-4">
            <div class="btn-group">
                <a href="?category=" class="btn btn-primary <?php echo empty($category_filter) ? 'active' : ''; ?>">All Categories</a>
                <?php
                foreach ($categories as $cat) {
                    $selectedClass = ($category_filter == $cat['categories']) ? 'active' : '';
                    echo "<a href='?category={$cat['categories']}' class='btn btn-secondary {$selectedClass}'>{$cat['categories']}</a>";
                }
                ?>
            </div>
        </div>

        <?php
        // Display products based on the selected category
        $product_query = "SELECT * FROM `product`";
        if (!empty($category_filter)) {
            $product_query .= " WHERE categories = '$category_filter'";
        }

        $product_query .= " ORDER BY name";
        $product_result = mysqli_query($conn, $product_query);
        $products = mysqli_fetch_all($product_result, MYSQLI_ASSOC);

        foreach ($products as $product) {
            // Display product information (customize as needed)
            echo "<div class='product'>";
            // Add more details as needed
            echo "</div>";
        }
        ?>



        <!-- <h3 style="text-align: center;">Top Products</h3> -->

        <div class="boxs-container">

            <?php
            // Modify the SQL query to include the category filter
            $select_products = mysqli_query($conn, "SELECT * FROM `product`" . ($category_filter ? " WHERE categories = '$category_filter'" : ""));



            while ($fetch_product = mysqli_fetch_assoc($select_products)) {
                $product_id = $fetch_product['product_id'];
                ?>

                <div class="item-row">
                    <form action="" method="post">
                        <div class="boxs">
                            <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" height="150px" width="150px">
                            <h3><?php echo $fetch_product['name']; ?></h3>
                            <div class="price">₱<?php echo number_format($fetch_product['price'], 2); ?></div>
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">

                            <!-- Button trigger modal -->
                            <!-- <button type="button" class="btnadd btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal<?php echo $product_id; ?>">
                                Add to Cart
                            </button> -->

                            <button class="CartBtn" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal<?php echo $product_id; ?>">
                            <span class="IconContainer"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" fill="rgb(17, 17, 17)" class="cart"><path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path></svg>
                            </span>
                            <p class="text">Add to Cart</p>
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal<?php echo $product_id; ?>" tabindex="-1"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Product Details</h1>
                                    </div>

                                    <div class="modal-body">
                                        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                                        <h3><?php echo $fetch_product['name']; ?></h3>
                                        <div class="price">₱<?= number_format($fetch_product['price'], 2); ?></div>
                                        <div class="stock">Stock: <?php echo $fetch_product['stock']; ?></div>

                                        <!-- Add an input field for quantity -->
                                        <input type="number" name="product_quantity" min="1" value="1">

                                        <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                                        <input type="hidden" name="product_stock" value="<?php echo $fetch_product['stock']; ?>">
                                        <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close
                                        </button>
                                        <!-- <button type="submit" class="btn btn-primary" value="Add to Cart"
                                                name="add_to_cart" <?php echo $fetch_product['id'] ?>>Add to Cart
                                        </button> -->
                                    
                                        <button class="CartBtn" type="submit" value="Add to Cart" name="add_to_cart" <?php echo $fetch_product['product_id']?>>
                                        <span class="IconContainer"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" fill="rgb(17, 17, 17)" class="cart"><path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path></svg>
                                        </span>
                                        <p class="text">Add to Cart</p>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <?php
            };
            ?>
        </div>

    </section>

</div>

</div>


  

<!-- custom js file link  -->

<script>



     // Wait for the document to load
     document.addEventListener("DOMContentLoaded", function () {
      // Find the message container
      const messageContainer = document.getElementById("message-container");

      // Check if the container is found
      if (messageContainer) {
          // Set a timeout to hide the message container after 3 seconds
          setTimeout(function () {
              messageContainer.style.display = "none";
          }, 3000); // 3000 milliseconds (3 seconds)
      }
  });
  
  
</script>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
