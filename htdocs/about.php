<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>About Us - Poole's Pools</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!--Developed by Gage Hutson-->

  <style>

    .about-section {
      padding: 60px 0;
    }

    .about-header {
      font-size: 36px;
      font-weight: 700;
      color: #1d3ede;
      margin-bottom: 30px;
    }

    .about-content p {
      font-size: 18px;
      line-height: 1.8;
      color: #333;
    }

    .about-content span {
      font-weight: 600;
      color: #00929c;
    }

    #gtco-main-nav::after,
    #gtco-main-nav::before {
      display: none;
    }
    .oval {
  width: 100%;
  position: absolute;
  bottom: 0;
  left: 0;
  text-align: center;
}

h4 {
  font-family: "Lato-Black";
  font-size: 60px;
  margin-bottom: 30px;
  font-weight: 800;
  display: flex;
  background: linear-gradient(270deg, #1d3ede, #00929c, #0db99d, #1d3ede); 
  background-size: 400% 400%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: smoothGradient 50s ease infinite;
  margin-bottom: 80px;
}

@keyframes smoothGradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid" id="gtco-main-nav">
    <div class="container">
      <a href="index.php" class="navbar-brand">Poole's Pools</a>
  
      <button class="navbar-toggler mr-4" type="button" data-toggle="collapse" data-target="#my-nav" aria-controls="my-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="bar1"></span>
        <span class="bar2"></span>
        <span class="bar3"></span>
      </button>
  
      <div id="my-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
        <!-- Cart, Login, Sign Up -->
        <div class="cart-login-wrapper d-flex flex-column flex-lg-row align-items-start align-items-lg-center mt-3 mt-lg-0 pr-lg-3">

          <!-- Cart -->
          <?php
            $cartCount = 0;
            if (isset($_SESSION['cart'])) {
              foreach ($_SESSION['cart'] as $item) {
                $cartCount += $item['qty'];
              }
            }
            ?>
            <!-- Cart -->
            <a href="cart.php" class="nav-link position-relative d-flex align-items-center mb-2 mb-lg-0 mr-lg-3 ml-lg-3" style="font-size: 1.2rem; color: #000;">
              <i class="fas fa-cart-shopping cart-icon-black"></i>
              <?php if ($cartCount > 0): ?>
                <span class="badge badge-pill badge-danger position-absolute" style="top: -5px; right: -18px; font-size: 0.7rem;">
                  <?= $cartCount ?>
                </span>
              <?php endif; ?>

              <span style="margin-right: -15px;" class="ml-1">Cart</span>
            </a>


          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Logged-in links -->
            <div class="nav-item dropdown mr-3 mb-3 mb-md-0 mb-lg-0">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg" style="font-size: 1.8rem; color: #333;"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right mt-2.99" aria-labelledby="accountDropdown" style="margin-right: -110px;">
                <a class="dropdown-item" href="account.php">Account Settings</a>
                <a class="dropdown-item" href="appointmentsmade.php">Appointments</a>
                <a class="dropdown-item" href="orderhistory.php">Order History</a>
              </div>
            </div>

            <a href="logout.php" class="btn btn-danger btn-login-signup text-uppercase ml-3 ml-md-0 ml-lg-0">Logout</a>
          <?php else: ?>
            <a href="#" onclick="document.getElementById('id01').style.display='block'" class="btn btn-outline-dark btn-login-signup text-uppercase mb-2 mb-lg-0 mr-lg-2">Login</a>
            <a href="signupmodal.php" class="btn btn-info btn-login-signup text-uppercase">Sign Up</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <div id="id01" class="modal">
      <form class="modal-content animate" action="login.php" method="post">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  
          </div>
        <div class="container">
        <h1 class="modaltitle">Login Or Create an Account</h1>
        <label for="psw" class="adjust-label"><b>Username</b></label>
    
          <input type="text" placeholder="Enter Username" name="uname" required style="border-radius: 10px;">
    
          <label for="psw" class="adjust-label"><b>Password</b></label>
    
          <div style="position: relative;">
            <input type="password" placeholder="Enter Password" name="psw" id="passwordInput" required style="border-radius: 10px;">
            <i class="fa-solid fa-eye" id="togglePassword" style="position: absolute; top: 51%; right: 30px; transform: translateY(-50%); color:#aaaaaa6e; cursor: pointer;"></i>
          </div>
          
            
          <button type="submit" id="submit">Login</button>
        </div>
    
        <div class="container" style="background-color:#ffffff">
          <span class="psw" id="create"><a href="signupmodal.php">Create Account</a></span>
          <span class="psw" id="pass"><a href="contact.php">Forgot password?</a></span>
        </div>
      </form>
    </div>

  <div class="container about-section" style="margin-top: 50px;">
    <div class="row justify-content-center">
      <div class="col-10">
        <h4 class="text-center justify-content-center">About </br> Poole's Pools</h4>
        <div class="about-content">
          <p>
            At <span>Poole's Pools</span>, we believe your backyard should be a source of peace, joy, and crystal-clear water. Founded with a passion for service and pool care, our mission has always been to deliver top-notch pool maintenance with honesty, affordability, and a smile.
          </p>
          <p>
            Our team is made up of certified pool technicians, dedicated to providing <span>hassle-free maintenance, repairs, and water testing</span> with precision and care. We use industry-leading techniques and modern equipment to ensure your pool stays clean, safe, and ready for relaxation.
          </p>
          <p>
            Whether you're looking for weekly maintenance, seasonal openings & closings, or advanced upgrades to your pool system, <span>Poole's Pools</span> is your go-to local expert.
          </p>
          <p>
            We're proud to serve our community with dependable service and friendly support. No contracts. No surprises. Just crystal clear results—every time.
            

          </p>
        </div>
      </div>
    </div>
  </div>




  <footer class="container-fluid" id="gtco-footer" style="margin-top: 450px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-6" id="contact">
          <h4 style="margin-left: 16px; font-size: 30px;">Contact Us</h4>
          <form action="https://formspree.io/f/movelqyg" method="POST">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            <textarea name="message" class="form-control" placeholder="Message" rows="5" required></textarea>
            <button type="submit" class="submit-button btn-link-hover" style="border: 0cap; margin-left: 13px;">Submit <i class="fa fa-angle-right" aria-hidden="true"></i></button>
          </form>
        </div>
        <div class="col-lg-6 d-flex align-items-start">
          <div class="w-100" style="margin-top: 150px;">
            <div class="row">
              <div class="col-6">
                <h4>Company</h4>
                <ul class="nav flex-column company-nav">
                  <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Services</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Appointments</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Shop</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">FAQ's</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
              </div>
              <div class="col-6">
                <h4>Our Services</h4>
                <ul class="nav flex-column services-nav">
                  <li class="nav-item"><a class="nav-link" href="services.html">Pool Cleaning</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Chemical Balancing</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Filter Maintenance</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Opening & Closing</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Leak Detection</a></li>
                  <li class="nav-item"><a class="nav-link" href="services.html">Tile & Surface Cleaning</a></li>
                </ul>
              </div>
              <div class="col-12">
                <p>&copy; 2025. All Rights Reserved. Designed by Poole's Pools.</p>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </footer>



  <!-- Scripts -->
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
  
    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  
  var modal = document.getElementById('id01');
  
  window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }
</script>
  <script src="js/jquery-3.3.1.slim.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
