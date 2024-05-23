<!DOCTYPE html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Grocer Ease</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="stylecss.css">

</head>

<style>
         body{
        height: 100vh;
        background-size: cover;
          background-position: center;
        background-image: url("image/homebg.jpg");
          background-repeat: no-repeat;
        align-items: center;
          justify-content: center;
        
      }
    </style>
    
<body>

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    
        <div class="container-fluid">
        <a class="navbar-brand"><i class="ri-shopping-cart-2-fill"></i> Grocer Ease</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a href="index.php"  class="nav-link">Home</a>
                    </li>
                    
                    <li class="nav-item dropdown ">
                        <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown">Stores</a>
                        <div class="dropdown-menu">
                            <a href="sm.php" class="dropdown-item">SM</a>
                            <a href="#" class="dropdown-item">PureGold</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About Us</a>
                    </li>
                    
                </ul>
                <ul class="nav navbar-nav ms-auto">
                <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" ><i class="ri-qr-scan-2-line"></i></a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Barcode Number</a>
                            <a href="#" class="dropdown-item">Product Image</a>
                        </div>
                <li class="nav-item">
                    
                        
                    </li>
                    <a href="mycart.php" ><button type="button" class="btn btn-outline-light"><i class="ri-shopping-cart-2-fill"></i></button></a>
                    <a href="logout.php" class="nav-link">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    

    <div class="container">
    <div class="row">
        <div class="col-lg-12 text-center border rounded bg-light my-5">
            </div>

                <div class="col-lg-9">

                        <table class="table">
                            <thead class="text-center">
                                <tr>
                                <th scope="col">Item No.</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Item Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col"></th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                              
                            </tbody>
                        </table>
                </div>

                    <div class="col-lg-3">
                        <div class="border bg-light rounded p-4">
                        <h4>Total:</h4>
                            <h5 class="text-right"></h5>
                            <form>

                            </form>
                        </div>
                    </div>
            </div>
        </div>

</body>
</html>