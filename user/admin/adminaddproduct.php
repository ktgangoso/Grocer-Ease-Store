<!-- <?php
require 'config.php';
if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
}
else{
    header("Location: adminlogin.php");
}

@include 'config.php';
if(isset($_POST['add_product'])){
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_FILES['p_image']['name'];
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = '../uploaded_img/'.$p_image;

    $insert_query = mysqli_query($conn, "INSERT INTO `product` (name, price, image) VALUES ('$p_name', '$p_price','$p_image')") or die('Query failed');

    if($insert_query){
        move_uploaded_file($p_image_tmp_name, $p_image_folder);
        // echo '<div class="addmessages"> Add Product Successfully </div>';
     }else{
        $message[] = 'could not add the product';
     }
  };

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
</head>
<body>

        <?php
        if(isset($message)){
            foreach($message as $message){
               echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
            };
         };
        ?>


<?php include 'adminnavbar.php'; ?>

        <div class="containersm">
            <section class="section1">
                <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
                    <h3>Add Product</h3>
                    <input type="text" name="p_name" placeholder="Enter the product name" class="box" required>
                    <input type="number" name="p_price" min="0" placeholder="Enter the product price" class="box" required>
                    <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
                    <input type="submit" value="Add Product" name="add_product" class="btnsm">
                </form>
            </section>
        </div>
        <script src="scripts.js"></script>
</body>
</html> -->