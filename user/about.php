<?php
require 'config.php';
if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
}
else{
    header("Location: user/login.php");
}
?>

<style>
    /* styles.css */
  .about p{
    text-align: center;
    font-size: 15px;
  }
  .about h2{
    text-align: center;
  }
  header {
  background-color: #333;
  color: #fff;
  padding: 1rem 0;
  text-align: center;
    
  }

  header h1 {
  margin: 0;
  color: #ffffff;
  }

  main {
  padding: 2rem;
  }

  .about {
  margin-bottom: 2rem;
  }

  .team {
    margin-bottom: 2rem;
    margin-top: 2.5rem;
  }


  .about h2,
  .team h2 {
  border-bottom: 2px solid #333;
  padding-bottom: 0.5rem;
  }

  .team ul {
  list-style: none;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 2rem;
  margin-top: 2rem;

  }

  .team li {
  text-align: center;

  }

  .team img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin-bottom: 0.5rem;
  }

  .footer {
      background-color: #f8f9fa;
      padding-top: 50px;
    }

    .footer h5 {
      color: #495057;
    }

    .footer ul {
      list-style: none;
      padding: 0;
    }

    .footer ul li {
      margin-bottom: 10px;
    }

    .social-icons a {
      color: #495057;
    }

    .subscribe-form {
      max-width: 400px;
      margin-top: 20px;
    }

    .subscribe-form input,
    .subscribe-form button {
      border-radius: 0;
    }

    .copyright {
      color: #6c757d;
    }

    .social-icons i {
      margin-right: 5px;
    }

  /* Responsive design using media queries */
  @media (max-width: 768px) {
    main {
        padding: 10px;
    }
    .team ul {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .team img {
        width: 100px;
        height: 100px;
    }
    .team li {
        text-align: center;
        margin-bottom: 1rem;
    }
  }

    @media (max-width: 480px) {
      .team ul {
          gap: 1rem;
      }
      .team img {
          width: 80px;
          height: 80px;
      }
  }

</style>

    <?php include 'navbar.php'; ?>

<header>
    <h1>About Us</h1>
  </header>
  <main>
    <section class="about">
      <h2>Our Company</h2>
      <p> Welcome to GrocerEase, the premier cross-platform website for price comparison and price checking. 
    Our goal is to arm consumers with accurate, current information so they can make wise judgments about their purchases and save money.

    <p>We at GrocerEase know how frustrating it can be to spend hours scouring many websites or visiting different shops in quest of the greatest deal on a product. 
Because of this, we created a robust and user-friendly platform that streamlines the entire procedure. 
You can easily compare costs between a variety of online and offline merchants with just a few clicks, ensuring that you never spend too much for the item you want.</p>
    <section class="team">
      <h2>Our Developer</h2>
      <ul>
        <li>
          <img src="image/member.png" alt="Team Member 1">
          <h3>Jonas Esparagoza</h3>
          <p>Co-founder / CEO</p>
        </li>
        <li>
          <img src="image/kevin.jpg" alt="Team Member 2">
          <h3>Kevin Gangoso</h3>
          <p>Software Engineer</p>
        </li>
        <li>
          <img src="image/bryan.jpg" alt="Team Member 2">
          <h3>Brayan Salomon</h3>
          <p>IT Project Manager</p>
        </li>
        <li>
          <img src="image/akmad.jpg" alt="Team Member 2">
          <h3>Akmad Saligan</h3>
          <p>Project Manager</p>
        </li>
      </ul>


    </section>
  </main>


  <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
    <div class="col mb-3">
      <a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
      <a style="text-decoration: none; color: #f8f9fa; font-size: 1.5rem;"  href="index.php"><i class="ri-shopping-cart-2-fill "></i> Grocer Ease Store</a>
      </a>
      <p style="color: #fff; font-size: 1.5rem" class=""> © 2023</p>
    </div>

    <div class="col mb-3">

    </div>

    <div class="col mb-3">
      <h5>Section</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0">Home</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0">Features</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0">Pricing</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0">FAQs</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0">About</a></li>
      </ul>
    </div>

    <div class="col mb-3">
    <h5>Follow Us</h5>
      <ul class="nav flex-column">
            <a style="text-decoration: none; color: #fff;" href="https://www.facebook.com/"><i class="ri-facebook-fill"></i>facebook</a>
            <a style="text-decoration: none; color: #fff;" href="https://twitter.com/?lang=en"><i class="ri-twitter-fill"></i>twitter</a>
            <a style="text-decoration: none; color: #fff;" href="https://www.instagram.com/"><i class="ri-instagram-fill"></i>instagram</a>
      </ul>
    </div>

    <div class="col mb-3">
      <h5>Section</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0 ">Home</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0 ">Features</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0 ">Pricing</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0 ">FAQs</a></li>
        <li class="nav-item mb-2"><a href="#" style="color: #fff;" class="nav-link p-0 ">About</a></li>
      </ul>
    </div>
  </footer>
  
  <script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
