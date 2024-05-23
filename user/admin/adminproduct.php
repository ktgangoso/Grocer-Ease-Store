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

function searchProducts($conn, $searchTerm)
{
    $query = "SELECT * FROM `product` WHERE name LIKE '%$searchTerm%' OR categories LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    return $result;
}

// if (!empty($_SESSION["id"])) {
//     $id = $_SESSION["id"];
//     $result = mysqli_query($conn, "SELECT * FROM acc WHERE id = $id");
//     $row = mysqli_fetch_assoc($result);
// } else {
//     header("Location: adminlogin.php");
// }

@include 'config.php';

// Search functionality
if (isset($_GET['submit']) && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $select_products = searchProducts($conn, $searchTerm);
} else {
    $select_products = mysqli_query($conn, "SELECT * FROM `product`");
}


if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_query = mysqli_query($conn, "DELETE FROM `product` WHERE product_id = $delete_id") or die('Query failed');
   if ($delete_query) {
       header('location: adminproduct.php');
       $message[] = 'Product has been deleted';
   } else {
       header('location: adminproduct.php');
       $message[] = 'Product could not be deleted';
   }
}

// update function
if (isset($_POST['update_product'])) {
    // Get other form field values
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_price = $_POST['update_p_price'];
    $update_p_stock = $_POST['update_p_stock']; // Assuming you have an input field for stock in your form
    $update_p_image = $_FILES['update_p_image']['name'];
    $update_p_category =  $_POST['category'];
    $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
    $update_p_image_folder = '../uploaded_img/' . $update_p_image;
    
    // Check if no new image is uploaded
    if (empty($update_p_image)) {
        $update_p_image = $_POST['existing_image'];
    }
 
    $update_p_stock = mysqli_real_escape_string($conn, $_POST['update_p_stock']);

    $update_query = mysqli_query($conn, "UPDATE `product` SET name = '$update_p_name', price = '$update_p_price', stock = '$update_p_stock', image = '$update_p_image', categories = '$update_p_category' WHERE product_id = '$update_p_id'");


    if ($update_query) {
        if (!empty($update_p_image)) {
            move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
        }
        $message[] = 'Product updated successfully';
        header('location: adminproduct.php');
    } else {
        $message[] = 'Product could not be updated';
        header('location: adminproduct.php');
    }
 }
 


 if (isset($_POST['add_product'])) {
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $p_category = mysqli_real_escape_string($conn, $_POST['category']);
    $p_stock = mysqli_real_escape_string($conn, $_POST['p_stock']);

    $p_image = $_FILES['p_image']['name'];
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = '../uploaded_img/' . $p_image;

    // Check for file upload errors
    if ($_FILES['p_image']['error'] != UPLOAD_ERR_OK) {
        die('File upload failed with error code ' . $_FILES['p_image']['error']);
    }

    $insert_query = mysqli_query($conn, "INSERT INTO `product` (name, price, stock, image, categories) VALUES ('$p_name', '$p_price', '$p_stock', '$p_image', '$p_category')") or die('Query failed');

    if ($insert_query) {
        // Move the uploaded file to the destination folder
        if (move_uploaded_file($p_image_tmp_name, $p_image_folder)) {
            $message[] = 'Product added successfully';
        } else {
            $message[] = 'Could not move the uploaded file';
        }
    } else {
        $message[] = 'Could not add the product';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admincss.css">
    <title>Admin</title>
</head>
<style>
    .search-results {
        margin-top: 20px;
    }

    .search-results h2 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .search-results table {
    border-collapse: collapse;
    width: 70rem;
    text-align: center;
    margin-top: 1rem;
    margin-left: 1.5rem;
    }

    .search-results th{
        padding: 1.5rem;
        text-align: center;
        text-transform: capitalize;
    }
    .search-results td {
        padding: 0.2rem;
        text-align: center;
    }

    .search-results th {
        background-color: #2e2e2e;
        color: #ffff;
        text-align: center;
    }

    .search-results tbody tr:hover {
        background-color: #a6a6a6;
    }

    .search-results .empty {
        margin-top: 10px;
        padding: 10px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        border-radius: 4px;
    }

    /* search */

    .form-inline {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 5rem;
    }

    .search-container {
        display: flex;
        align-items: center;
    }

    .form-control {
        width: 70rem;
        margin-right: 10px;
    }

    .btn {
        padding: 8px 12px;
    }

    /* Optional: Add media query for responsiveness */
    @media (max-width: 576px) {
        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }

        .form-control {
            width: 100%;
            margin-bottom: 10px;
            margin-right: 0;
        }
    }

    .btns {
        background-color: #3498db; /* Blue color */
        color: #fff; /* White text color */
        border: 1px solid #3498db; /* Blue border */
        border-radius: 10px;
        padding: 8px 16px; /* Padding around the text */
        cursor: pointer; /* Cursor style on hover */
        transition: background-color 0.3s ease; /* Smooth transition for background color */
    }

    .btns:hover {
        background-color: #0056b3;
        color: #fff;
    }

    #empty-message {
        padding: 10px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        border-radius: 4px;
        display: inline-block;
        width: 70rem;
        text-align: center;
        margin-top: 1rem;
        margin-left: 1.5rem;
        text-transform: capitalize;
    }


    .btna {
    display: inline-block;
    padding: 8px 16px;
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

    .btna:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .btnu {
        background-color: #3498db; /* Blue color */
        width: 100%;
        text-transform: capitalize;
        color: #fff; /* White text color */
        border: 1px solid #3498db; /* Blue border */
        border-radius: 10px;
        padding: 8px 16px; /* Padding around the text */
        cursor: pointer; /* Cursor style on hover */
        transition: background-color 0.3s ease; /* Smooth transition for background color */
    }

    .btnu:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    .btna:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btnadd{
    width: 10rem;
    display: inline-block;
    padding: 8px 16px;
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

    .btnclose{
    width: 10rem;
    display: inline-block;
    padding: 8px 16px;
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

    .btnupdate{
    width: 10rem;
    display: inline-block;
    padding: 8px 16px;
    margin-top: 2rem;
    margin-left: 4rem;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    user-select: none;
    border: 1px solid transparent;
    border-radius: 10px;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .category-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
    max-width: auto; /* Set a maximum width for the container */
    margin: 0 auto; /* Center the container horizontally on the page */
    }

    .category-buttons a {
    flex: 1;
    max-width: auto; /* Limit the maximum width of each button */
    font-size: 15px;
    padding: .5rem;

    }


</style>


    <body>
    <?php include 'adminnavbar.php'; ?>
    


        
        <div class="container">

        <form class="form-inline" method="GET">

            <div class="search-container">
                <input class="form-control" type="search" aria-label="Search" value="<?php if (isset($_GET['search'])) {
                echo $_GET['search'];
                } ?>" name="search" placeholder="Search Product">
                <button class="btns btn-primary" type="submit" name="submit">Search</button>
            </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btna btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="ri-add-line"></i> Add Product
            </button>

        </form>

<section class="search-results">
            
        <?php
        if (isset($_GET['submit']) && isset($_GET['search'])) {
            $searchTerm = $_GET['search'];

            if (!empty($searchTerm)) {
                $search_results = searchProducts($conn, $searchTerm);

                if (mysqli_num_rows($search_results) > 0) {
                    echo "<section class='search-results'>";
                    echo "<table>";
                    echo "<thead>";
                    echo "<th>product image</th>";
                    echo "<th>product name</th>";
                    echo "<th>product price</th>";
                    echo "<th>Stock</th>";
                    echo "<th>Delete</th>";
                    echo "<th>Update</th>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = mysqli_fetch_assoc($search_results)) {
                        echo "<tr>";
                        echo "<td data-cell='Image'><img src='../uploaded_img/{$row['image']}' height='50px' width='50px' alt=''></td>";
                        echo "<td data-cell='Name'>{$row['name']}</td>";
                        echo "<td data-cell='Price'>₱ {$row['price']}</td>";
                        echo "<td data-cell='Stock'>{$row['stock']}</td>";
                        echo "<td data-cell='Delete'><a style='text-decoration: none;' href='adminproduct.php?delete={$row['product_id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this?\");'><i class='fas fa-trash'></i> delete </a></td>";
                        echo "<td data-cell='Update'>
                        <button type='button' class='btn btn-primary update-btn' data-bs-toggle='modal' data-bs-target='#update{$row['product_id']}' data-product-id='{$row['product_id']}'>
                            <i class='fas fa-edit'></i> update
                        </button>
                    </td>";
                        echo "</tr>";
                    }

                    

                    echo "</tbody>";
                    echo "</table>";
                    echo "</section>";
                } else {
                    echo "<div class='empty'>No results found for '{$searchTerm}'</div>";
                }
            } else {
                echo "<div id='empty-message' class='empty'>Please enter a product</div>";
                echo "<script>
                        setTimeout(function() {
                            document.getElementById('empty-message').style.display = 'none';
                        }, 3000);
                    </script>";
            }
        }
        ?>


</section>


<!-- add product modal -->
            
<!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                <div class="containersm">
                        <section class="section1">
                        <form action="" method="POST" class="add-product-form" enctype="multipart/form-data">

                            <h3 class="text-center mb-4">Add Product</h3>

                            <div class="mb-3">
                            <label for="p_name" class="form-label">Product Name:</label>
                            <input type="text" name="p_name" class="form-control box" placeholder="Enter the product name" required>
                            </div>

                            <div class="mb-3">
                            <label for="p_price" class="form-label">Product Price:</label>
                            <input type="number" name="p_price" min="0" class="form-control box" placeholder="Enter the product price" required>
                            </div>

                            <div class="mb-3">
                            <label for="p_image" class="form-label">Product Image:</label>
                            <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="form-control box" required>
                            </div>

                            <div class="mb-3">
                            <label for="p_stock">Stock:</label>
                            <input type="number" name="p_stock" min="0" class="form-control box" placeholder="Enter Stock of Product " required>
                            </div>

                            <!-- Change name attribute from 'p_category' to 'category' -->
                            <div class="mb-3">
                                <label for="p_category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="Powdered Milk">Powdered Milk</option>
                                    <option value="Powdered Chocolate">Powdered Chocolate</option>
                                    <option value="Cereals">Cereals</option>
                                    <!-- Add similar lines for other categories -->
                                    <option value="Snacks">Snacks</option>
                                    <option value="Beverages">Beverages</option>
                                    <option value="Personal Care">Personal Care</option>
                                    <option value="Canned Foods">Canned Foods</option>
                                    <option value="Chocolate">Chocolate</option>
                                    <option value="Frozen Foods">Frozen Foods</option>
                                    <option value="Condiments">Condiments</option>
                                </select>
                                </select>
                            </div>



                            <div class="mb-3 text-center">
                            <button type="button" class="btnclose btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add Product" name="add_product" class="btnadd btn-primary btn-sm">
                            </div>

                            </form>
                        </section>
                    </div>
                    
                </div>
                </div>
            </div>
        </div>


        
<section class="display-product-table">


<table>

   <thead>
      <th>product image</th>
      <th>product name</th>
      <th>product price</th>
      <th>Stock</th>
      <th>Delete</th>
      <th>Update</th>
   </thead>

   <tbody>


    <!-- <form method="GET" class="form-inline" id="categoryFilterForm">
        <label class="mr-2" for="category">Search Categories:</label>
        <select class="form-select mr-2" name="category" id="category">
        <option value="">All Categories</option>
        <option value="Powdered Milk" selected>Powdered Milk</option>
        <option value="Powdered Chocolate">Powdered Chocolate</option>
        <option value="Cereals">Cereals</option>
        <option value="Snacks">Snacks</option>
        <option value="Beverages">Beverages</option>
        <option value="Personal Care">Personal Care</option>
        <option value="Canned Foods">Canned Foods</option>
        <option value="Chocolate">Chocolate</option>

        <?php foreach ($categories as $cat) : ?>
        <option value="<?php echo $cat['categories']; ?>" <?php echo ($cat['categories'] == $category_filter) ? 'selected' : ''; ?>>
        <?php echo $cat['categories']; ?>
        </option>
        <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Apply Filter</button>
    </form> -->


        <!-- Display categories as a dropdown for filtering -->
        <div class="category-buttons">
    <a href="?category=" class="btn <?php echo empty($category_filter) ? 'btn-primary' : 'btn-secondary'; ?>">All Categories</a>
    <?php
    $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

    // Fetch all unique categories from the database
    $category_query = mysqli_query($conn, "SELECT DISTINCT categories FROM `product`");
    $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC);

    // Display category buttons
    foreach ($categories as $cat) {
        $selectedClass = ($category_filter == $cat['categories']) ? 'btn-primary' : 'btn-secondary';
        echo "<a href='?category={$cat['categories']}' class='btn {$selectedClass}'>{$cat['categories']}</a>";
    }
    ?>
</div>



      <?php
      // Get the selected category filter
        $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

        // Fetch all unique categories from the database
        $category_query = mysqli_query($conn, "SELECT DISTINCT categories FROM `product`");
        $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC);

                  $select_products = mysqli_query($conn, "SELECT * FROM `product`" . ($category_filter ? " WHERE categories = '$category_filter'" : ""));
        //  $select_products = mysqli_query($conn, "SELECT * FROM `product`");
         if(mysqli_num_rows($select_products) > 0){
            while($row = mysqli_fetch_assoc($select_products)){
      ?>

      <tr>
         <td data-cell="Image" ><img src="../uploaded_img/<?php echo $row['image']; ?>" height="50px" width="50px" alt=""></td>
         <td data-cell="Name"><?php echo $row['name']; ?></td>
         <td data-cell="Price">₱ <?php echo number_format($row['price'], 2); ?></td>
         <td data-cell="Stock"><?php echo $row['stock']; ?></td>
         <td data-cell="Delete">
            <a style="text-decoration: none;" href="adminproduct.php?delete=<?php echo $row['product_id']; ?>" class="delete-btn" 
            onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
         </td>
         <td data-cell="Update">
        <!-- Button trigger modal -->
        <button type="button" class="btnu btn-primary" data-bs-toggle="modal" data-bs-target="#update<?php echo $row['product_id']; ?>">
                <i class="fas fa-edit"></i> update
        </button>
         </td>
      


        </tr>

        <!-- update product modal  -->
<!-- Modal -->
<div class="modal fade" id="update<?php echo $row['product_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        



                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <img src="../uploaded_img/<?php echo $row['image']; ?>" class="mx-auto d-block" height="100px" width="100px" alt="">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="update_p_id" value="<?php echo $row['product_id']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="update_p_name">Product Name:</label>
                        <input type="text" class="form-control" required name="update_p_name" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="update_p_price">Product Price:</label>
                        <input type="number" min="0" class="form-control" required name="update_p_price" value="<?php echo number_format($row['price'], 2); ?>">
                    </div>
                    <div class="form-group">
                        <label for="update_p_stock">Product Stock:</label>
                        <input type="number" min="0" class="form-control" required name="update_p_stock" value="<?php echo $row['stock']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="update_p_image">Product Image:</label>
                        <input type="file" class="form-control-file" name="update_p_image" accept="image/png, image/jpg, image/jpeg">
                    </div>
                    <input type="hidden" name="existing_image" value="<?php echo $row['image']; ?>">

                        <!-- Change name attribute from 'p_category' to 'category' -->
                        <div class="mb-3">
                            <label for="p_category" class="form-label">Category</label>
                            <select class="form-select" id="update_category" name="category">
                            <option value="Powdered Milk" <?php echo ($row['categories'] === 'Powdered Milk') ? 'selected' : ''; ?>>Powdered Milk</option>
                            <option value="Powdered Chocolate" <?php echo ($row['categories'] === 'Powdered Chocolate') ? 'selected' : ''; ?>>Powdered Chocolate</option>
                            <option value="Cereals" <?php echo ($row['categories'] === 'Cereals') ? 'selected' : ''; ?>>Cereals</option>
                            <!-- Add similar lines for other categories -->
                            <option value="Snacks" <?php echo ($row['categories'] === 'Snacks') ? 'selected' : ''; ?>>Snacks</option>
                            <option value="Beverages" <?php echo ($row['categories'] === 'Beverages') ? 'selected' : ''; ?>>Beverages</option>
                            <option value="Personal Care" <?php echo ($row['categories'] === 'Personal Care') ? 'selected' : ''; ?>>Personal Care</option>
                            <option value="Canned Foods" <?php echo ($row['categories'] === 'Canned Foods') ? 'selected' : ''; ?>>Canned Foods</option>
                            <option value="Chocolate" <?php echo ($row['categories'] === 'Chocolate') ? 'selected' : ''; ?>>Chocolate</option>
                            <option value="Frozen Foods" <?php echo ($row['categories'] === 'Frozen Foods') ? 'selected' : ''; ?>>Frozen Foods</option>
                            <option value="Condiments" <?php echo ($row['categories'] === 'Condiments') ? 'selected' : ''; ?>>Condiments</option>
                            <!-- Add more categories as needed -->

                            </select>
                        </div>

                    
                    <button type="submit" class="btnupdate btn-primary" name="update_product">Update</button>
                    <button type="button" class="btnclose btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>


      </div>
    </div>
  </div>
</div>


      <?php
         };    
         }else{
            echo "<div class='empty'>no product added</div>";
         };
      ?>
   </tbody>
</table>

</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="scripts.js"></script>
    </body>
</html>