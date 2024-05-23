<?php
require 'config.php';
if(!empty($_SESSION["user_id"])){
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
}
else{
    header("Location: login.php");   
}

// heading editable
// Fetch the dynamic heading from the database
$result = $conn->query("SELECT heading_content FROM content WHERE id = 1");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dynamicHeading = $row['heading_content'];
} else {
    $dynamicHeading = "Default Heading";  // Set a default value or handle it accordingly
}


// Fetch data for another heading from the database
$resultAnotherHeading = $conn->query("SELECT heading_content FROM content WHERE id = 2");

if ($resultAnotherHeading->num_rows > 0) {
    $rowAnotherHeading = $resultAnotherHeading->fetch_assoc();
    $currentAnotherHeading = $rowAnotherHeading['heading_content'];
} else {
    $currentAnotherHeading = "Default Another Heading";
}






// growth company title

// Fetch data for the additional heading from the database
$resultAdditionalHeading = $conn->query("SELECT heading_content FROM content WHERE id = 3");

if ($resultAdditionalHeading->num_rows > 0) {
    $rowAdditionalHeading = $resultAdditionalHeading->fetch_assoc();
    $currentAdditionalHeading = $rowAdditionalHeading['heading_content'];
} else {
    $currentAdditionalHeading = "Default Additional Heading";
}

// growth company paragraph
// Fetch data for the additional paragraph from the database
$currentAdditionalParagraph = "Default Additional Paragraph";




// twitter dynamuic

// Fetch the current Twitter link href from the database
$resultTwitterLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 3");

if ($resultTwitterLinkHref->num_rows > 0) {
    $rowTwitterLinkHref = $resultTwitterLinkHref->fetch_assoc();
    $currentTwitterLinkHref = $rowTwitterLinkHref['twitter_link_href'];
} else {
    $currentTwitterLinkHref = ""; // Set a default value if no data is found
}

// facebook dynamic

$resultFacebookLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 2");

if ($resultFacebookLinkHref->num_rows > 0) {
    $rowFacebookLinkHref = $resultFacebookLinkHref->fetch_assoc();
    $currentFacebookLinkHref = $rowFacebookLinkHref['twitter_link_href'];
} else {
    $currentFacebookLinkHref = ""; // Set a default value if no data is found
}

// instagram dynamic
// Fetch the current Instagram link href from the database
$resultInstagramLinkHref = $conn->query("SELECT twitter_link_href FROM content WHERE id = 1");

if ($resultInstagramLinkHref->num_rows > 0) {
    $rowInstagramLinkHref = $resultInstagramLinkHref->fetch_assoc();
    $currentInstagramLinkHref = $rowInstagramLinkHref['twitter_link_href'];
} else {
    $currentInstagramLinkHref = ""; // Set a default value if no data is found
}

?>



<style>
  .social-links ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
  }

  .social-links li {
    /* display: inline; */
    margin-right: 10px; /* Adjust the spacing between icons if needed */
  }

    /* p{
    margin-top: 2rem;
    } */

    /* .section-title h2 {
        font-size: 32px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 20px;
        padding-bottom: 20px;
        position: relative;
        color: #37517e;
    } */

      /* Style for the ratings and reviews section */
  .ratings-reviews {
    background-color: #ffffff; /* Set background color to white */
  }
</style>

    <?php include 'navbar.php'; ?>  

    <!-- <div class="container">
        <div class="content">
            
            <h1 style="font-weight: 700;"></h1>
            <p style="font-size: 20px;"></p>

            <button class="cta" >
        <svg viewBox="0 0 46 16" height="10" width="30" xmlns="http://www.w3.org/2000/svg" id="arrow-horizontal">
            <path transform="translate(30)" d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" data-name="Path 10" id="Path_10"></path>
        </svg>
    </button>

        </div>
    </div> -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

  <!-- Template Main CSS File -->
  <link href="index.css" rel="stylesheet">

</head>

<body>


<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center" style="background-image: url('image/homecover.jpg'); background-size: cover; background-position: center;">


  <div class="container">
    <div class="row">
      <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
        <!-- <h1>Welcome to Grocer Ease Store Enjoy Shopping </h1> -->
        <!-- <h2>Discover amazing products and services.</h2> -->
        <h1> <?php echo $dynamicHeading; ?></h1>
        <h2><?php echo $currentAnotherHeading; ?></h2>
        <div class="d-flex justify-content-center justify-content-lg-start">
          <!-- <a href="store.php" class="btn btn-primary">Shop now</a> -->

          <button class="buttons">
          <div class="default-btn">
          <i class="bi bi-search"></i>
            <!-- <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="#FFF" height="20" width="20" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle r="3" cy="12" cx="12"></circle></svg> -->
            <span> Search Product?</span>
          </div>
          <div class="hover-btn">
          <i class="bi bi-cart4"></i>
            <!-- <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="#ffd300" height="20" width="20" viewBox="0 0 24 24"><circle r="1" cy="21" cx="9"></circle><circle r="1" cy="21" cx="20"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> -->
            <a style="text-decoration: none; color: #fff;" href="store.php"> Shop Now</a>
          </div>
        </button>

        </div>
      </div>
      <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
      </div>
    </div>
  </div>

</section><!-- End Hero -->


  <main id="main">

<!-- ======= About Us Section ======= -->
<section id="about" class="about">
  <div class="section-title">
    <h2 class="text-center mb-4">About Us</h2>
  </div>
  <hr style="border: 2px solid black;">
  <div class="container" data-aos="fade-up">
    <div class="row content">
      <div class="col-lg-5 align-items-stretch order-2 order-lg-1 img" style='background-image: url("image/aboutimg.png"); margin-right: 5rem;' data-aos="zoom-in" data-aos-delay="150">&nbsp;</div>
      <div class="col-lg-6 order-1 order-lg-2">
          Welcome to GrocerEase, the premier cross-platform website for price comparison and price checking. Our goal is to arm consumers with accurate,
           current information so they can make wise judgments about their purchases and save money.
        </p>
          We at GrocerEase know how frustrating it can be to spend hours scouring many websites or visiting different shops in quest of the greatest deal on a product.
          Because of this, we created a robust and user-friendly platform that streamlines the entire procedure.
          You can easily compare costs between a variety of online and offline merchants with just a few clicks,
          ensuring that you never spend too much for the item you want.
        </p>
      </div>
    </div>
  </div>
</section><!-- End About Us Section -->


<!-- ======= Why Us Section ======= -->
<section id="why-us" class="why-us section-bg">
  <div class="container-fluid" data-aos="fade-up">

  <div class="row justify-content-center align-items-center">

    <div class="col-lg-6 d-flex flex-column align-items-center order-2 order-lg-1">


        <div class="content">
             <h3>GrocerEase: A Journey of Innovation and Consumer Empowerment</h3>

          </h3>
          <h5>GrocerEase was established after thorough market research to identify consumer frustrations in price comparison, aiming to improve the shopping experience</h5>
          </p>
        </div>

      </div>

      <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img" style='background-image: url("image/grocer.png");' data-aos="zoom-in" data-aos-delay="150">&nbsp;</div>
    </div>

  </div>
</section><!-- End Why Us Section -->


<!-- ======= Services Section ======= -->
<section id="services" class="services section-bg">

<div class="section-title text-center mb-4">
      <h2>Services</h2>
    </div>
    
  <div class="container" data-aos="fade-up">

    <div class="row">
      <div class="col-xl-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
        <div class="icon-box">
          <div class="icon"><i class="bx bxl-dribbble"></i></div>
          <h4><a>Shopping Cart and Checkout</a></h4>
          <p>Develop a responsive shopping cart and a streamlined checkout process. Include features like multiple payment options, guest checkout, and order confirmation.</p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
        <div class="icon-box">
          <div class="icon"><i class="bx bx-file"></i></div>
          <h4><a>User Accounts and Authentication</a></h4>
          <p>Allow users to create accounts for a personalized experience. Implement a secure authentication system to protect user information.</p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300">
        <div class="icon-box">
          <div class="icon"><i class="bx bx-tachometer"></i></div>
          <h4><a>Search and Navigation</a></h4>
          <p>Implement an efficient search functionality and navigation system to help users find products easily. Include filters, sorting options, and categories.</p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="400">
        <div class="icon-box">
          <div class="icon"><i class="bx bx-layer"></i></div>
          <h4><a>Responsive Design</a></h4>
          <p>Ensure that your website is responsive and works well on various devices, including desktops, tablets, and smartphones.</p>
        </div>
      </div>

    </div>

  </div>
</section><!-- End Services Section -->



<!-- ======= Team Section ======= -->
<section id="team" class="team section-bg">

    <div class="section-title">
          <h2>Team</h2>
        </div>

      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="image/member.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Jonas Esparagoza</h4>
                <span>Chief Executive Officer</span>
                <div class="social">
                  <a href="https://twitter.com/"><i class="ri-twitter-fill"></i></a>
                  <a href="https://www.facebook.com/"><i class="ri-facebook-fill"></i></a>
                  <a href="https://www.instagram.com/"><i class="ri-instagram-fill"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="image/kevin.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Kevin Gangoso</h4>
                <span>Software Engineer</span>
                <div class="social">
                  <a href="https://twitter.com/"><i class="ri-twitter-fill"></i></a>
                  <a href="https://www.facebook.com/"><i class="ri-facebook-fill"></i></a>
                  <a href="https://www.instagram.com/"><i class="ri-instagram-fill"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="image/bryan.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Bryan Salomon</h4>
                <span>CTO</span>
                <div class="social">
                  <a href="https://twitter.com/"><i class="ri-twitter-fill"></i></a>
                  <a href="https://www.facebook.com/"><i class="ri-facebook-fill"></i></a>
                  <a href="https://www.instagram.com/"><i class="ri-instagram-fill"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="400">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="image/akmad.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Akmad Saligan</h4>
                <span>Accountant</span>
                <div class="social">
                  <a href="https://twitter.com/"><i class="ri-twitter-fill"></i></a>
                  <a href="https://www.facebook.com/"><i class="ri-facebook-fill"></i></a>
                  <a href="https://www.instagram.com/"><i class="ri-instagram-fill"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
</section>
<!-- End Team Section -->

<!--<section id="ratingsAndReviews" class="ratings-reviews">
    <div class="section-title">
        <h2>Ratings and Reviews</h2>
    </div>

    <div class="container" data-aos="fade-up">

        <div class="row">

            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
                <div class="member d-flex align-items-start">
                    <div class="pic"><img src="image/member.png" class="img-fluid" alt="Customer 2"></div>
                    <div class="member-info">
                        <h4>Customer 1</h4>
                        <span>Verified Buyer</span>
                        <p>Purchased the product and it exceeded my expectations. Quality is top-notch!</p>
                        <div class="ratings">
                            ★★★★☆
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
                <div class="member d-flex align-items-start">
                    <div class="pic"><img src="image/member.png" class="img-fluid" alt="Customer 2"></div>
                    <div class="member-info">
                        <h4>Customer 2</h4>
                        <span>Happy Shopper</span>
                        <p>The product arrived on time and was exactly as described. Highly recommended!</p>
                        <div class="ratings">
                            ★★★★☆
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
              <div class="member d-flex align-items-start">
                  <div class="pic"><img src="image/member.png" class="img-fluid" alt="Customer 2"></div>
                    <div class="member-info">
                        <h4>Customer 3</h4>
                        <span>Verified Buyer</span>
                        <p>Purchased the product and it exceeded my expectations. Quality is top-notch!</p>
                        <div class="ratings">
                            ★★★★☆
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
                <div class="member d-flex align-items-start">
                    <div class="pic"><img src="image/member.png" class="img-fluid" alt="Customer 2"></div>
                    <div class="member-info">
                        <h4>Customer 4</h4>
                        <span>Verified Buyer</span>
                        <p>Purchased the product and it exceeded my expectations. Quality is top-notch!</p>
                        <div class="ratings">
                            ★★★★☆
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->



    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
    <div class="section-title">
        <h2>Location</h2>
      </div>

      <div class="container" data-aos="fade-up">
    <div class="row">
      <!-- Text Content on the Left -->
      <div class="col-lg-5 d-flex align-items-stretch">
        <div class="info">
          <div class="address">
            <h4><i class="bi bi-geo-alt"></i> Location:</h4>
            <p>Taguig City University</p>
          </div>
          <div class="email">
            <h4><i class="bi bi-envelope"></i> Email:</h4>
            <p>info@example.com</p>
          </div>
          <div class="phone">
            <h4><i class="bi bi-phone"></i>Call:</h4>
            <p>09123456789</p>
          </div>
        </div>
      </div>

      <!-- Map on the Right -->
      <div class="col-lg-7 d-flex align-items-stretch">
        <div class="info">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d121.0522242!3d14.4894722!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf149d091943%3A0xbc9d88bd46e66c90!2sTaguig%20City%20University!5e0!3m2!1sen!2sph!4v1578393358884!5m2!1sen!2sph" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>


<!-- <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="name">Your Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
              </div>
              <div class="form-group col-md-6">
                <label for="name">Your Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>
            </div>
            <div class="form-group">
              <label for="name">Subject</label>
              <input type="text" class="form-control" name="subject" id="subject" required>
            </div>
            <div class="form-group">
              <label for="name">Message</label>
              <textarea class="form-control" name="message" rows="10" required></textarea>
            </div>
            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>
            </div>
            <div class="text-center"><button type="submit">Send Message</button></div>
          </form>
        </div>
</div> -->

  </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>Grocer Ease Store</h3>
            <p>
              A108 Adam Street <br>
              Taguig City University<br>
              Philippines <br><br>
              <strong>Phone:</strong> 09123456789<br>
              <strong>Email:</strong> info@example.com<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#hero">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#about">About Us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#why-us">Company Growth</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#services">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#contact">Contact Us</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Languages</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">English</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Tagalog</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Chinesse</a></li>
              <!-- <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li> -->
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4 style="color: #fff; ">Follow Us</h4>
            <div class="social-links mt-3">
              <ul>
              <li><a href="<?php echo $currentTwitterLinkHref; ?>" class="twitter"><i class="bi bi-twitter"></i> Twitter</a> </li>
                <li><a href="<?php echo $currentFacebookLinkHref; ?>" class="facebook"><i class="bi bi-facebook"></i> Facebook</a> </li>
                <li><a href="<?php echo $currentInstagramLinkHref?>" class="instagram"><i class="bi bi-instagram"></i> Instagram</a> </li>
              </ul>
              <!-- <a href="https://twitter.com/" class="twitter"><i class="bi bi-twitter"></i></a>
              <a href="https://www.facebook.com/" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/" class="instagram"><i class="bi bi-instagram"></i></a> -->
              <!-- <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> -->
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
         © 2023 Grocer Ease Store. All Rights Reserved 
      </div>

    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <!-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> -->



</body>

</html>
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
