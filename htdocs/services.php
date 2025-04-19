<?php session_start(); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="owl-carousel/assets/owl.carousel.min.css" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Pool Services - Poole's Pools</title>
</head>

<!--Developed by Gage Hutson-->

<style>

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #d6d6d6;
  box-sizing: border-box;
  color: #000000;
  background-color: #ffffff;
}

input[type="text"]:focus,
input[type="password"]:focus {
  outline: none;
  border: 1px solid #727272; 
  background-color: #ffffff;
  color: #000000;
}



#id01 button {
    background: -webkit-linear-gradient(left, #1d3ede, #00929c);
  background: -ms-linear-gradient(left, #1d3ede, #01e6f8);
  background: -moz-linear-gradient(left, #1d3ede, #01e6f8);
  background: -o-linear-gradient(left, #1d3ede, #01e6f8);
  color: rgb(255, 255, 255);
  padding: 14px 20px;
  margin: 8px 0;
  margin-top: 35px;
  border: none;
  cursor: pointer;
  width: 100%;
  vertical-align: middle;
  line-height: normal;
}

button.loginbutton {
  max-width: 250px;
  margin: 20px auto;
  display: block;
  position: relative;
  border-radius: 30px;
  top: 0;
  left: 0;
}



button:hover {
  opacity: 0.98;
}


.imgcontainer {
    text-align: center;
    position: relative;
}


.container {
  padding: 16px;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
}


span.psw{
  display: flex;
  justify-content: space-between; 
  gap: 30px; 
  margin-bottom: 20px;
  align-items: center !important;
  justify-content: center;
}





span.psw a {
  display: block;
  color: #000000;
  text-decoration: none;
  font-size: medium;
}

span.psw a:hover {
  color: #000000b7;
  text-decoration: underline; 
}


.modal {
  display: none;
  position: fixed;
  z-index: 10;
  left: 0;
  top: 50px;
  width: 100%;
  height: 100%;
  overflow: auto;
  z-index: 1050 !important;
  padding-top: 60px;
  margin: 0 auto;
}


.modal-content {
  background: #ffffff;
  border-radius: 12px;
  margin: 5% auto;
  padding: 0;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  animation: fadeIn 0.4s ease-in-out;
}

label,
label b {
  color: #000000 !important;
  padding-bottom: -30px;
}

label.adjust-label {
  position: relative;
  bottom: -5px; 
  left: 15px;
}

.close {
  position: absolute;
  right: 10px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}


input::placeholder {
  color: #cccccca1 !important;
  opacity: 1;
}

#id01 input[type="text"],
#id01 input[type="password"] {
  width: 340px;
  padding: 6px 10px;
  font-size: 14px;
  margin: 10px auto;
  display: block;
  box-sizing: border-box;
}


#submit {
  margin-top: 30px;
  margin-bottom: 10px;
  margin-left: auto;
  margin-right: auto;
  width: 300px;
  border-radius: 10px;
  display: block;
  color: white;
  border: none;
  cursor: pointer;
}


.modaltitle{
    font-size: 30px; 
    color: #000;
    display: block;
    text-align: center;
    margin-top: 40px;
    margin-bottom: 20px;
    
}


.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatezoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}

@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
}

@media screen and (max-width: 600px) {
  button.loginbutton {
    position: static;
    margin: 20px auto;
    display: block;
  }
  .navbar-brand{
    padding-left: 20px;
  }

}
#gtco-main-nav::after,
#gtco-main-nav::before {
  display: none;
}

#gtco-main-nav {
  position: sticky;
  top: 0;
  z-index: 1000;
  background-color: white; 
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}
#services .col-lg-4 p {
  text-align: center !important;
  width: 100% !important;
  margin: 0 auto !important;
  display: block !important;
  padding-bottom: 20px;
}

h2 {
  font-size: 50px ;
  font-weight: 800;
  background: linear-gradient(270deg, #1d3ede, #00929c, #0db99d, #1d3ede); 
  background-size: 400% 400%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: smoothGradient 8s ease infinite;
}

h3 {
  font-size: 50px ;
  font-weight: 800;
  color: #6086ee !important; 
  background-size: 400% 400%;
}

</style>
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
  
  <div class="container-fluid gtco-features" id="services" style="margin-top: -20px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 text-center" style="padding: 40px !important;">
          <h2 style="font-size: 60px; ">Pool Services</h2> 
          <p>We offer a wide range of pool maintenance/repair services to keep your pool clear and running great!</p>

          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="appointments.php" class="btn btn-primary d-inline-block mt-3 btn-link-hover" style="border: none;">
              Schedule appointment <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
          <?php else: ?>
            <a href="signupmodal.php" class="btn btn-primary d-inline-block mt-3 btn-link-hover" style="border: none;">
              Schedule appointment <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
          <?php endif; ?>

        </div>
        
        <div class="col-lg-8" id="services-list">
          <div class="row">
            <div class="col-md-6">
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/1.png" style="margin-bottom: 15px;" alt="Pool Cleaning"></div>
                <div class="card-body">
                  <h3 class="card-title">Pool Cleaning</h3>
                  <p class="card-text">Scheduled cleanings to keep your pool spotless and balanced all year round.</p>
                </div>
              </div>
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/6.png" style="margin-bottom: 15px;" alt="Pump Repair"></div>
                <div class="card-body">
                  <h3 class="card-title">Pump & Filter Repair</h3>
                  <p class="card-text">Fast and reliable repair services to keep your equipment in top shape.</p>
                </div>
              </div>
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/4.png" style="margin-bottom: 15px;" alt="Leak Detection"></div>
                <div class="card-body">
                  <h3 class="card-title">Leak Detection</h3>
                  <p class="card-text">We locate and repair hidden leaks quickly to prevent costly water loss and damage.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/5.png" style="margin-bottom: 15px;" alt="Chemical Balancing"></div>
                <div class="card-body">
                  <h3 class="card-title">Chemical Balancing</h3>
                  <p class="card-text">We test and adjust chemical levels to ensure safe and clean swimming conditions.</p>
                </div>
              </div>
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/3.png" style="margin-bottom: 15px;" alt="Pool Opening/Closing"></div>
                <div class="card-body">
                  <h3 class="card-title">Seasonal Opening & Closing</h3>
                  <p class="card-text">Get your pool ready for summer or properly closed for winter with our seasonal services.</p>
                </div>
              </div>
              <div class="card text-center">
                <div class="oval"><img class="card-img-top" src="images/2.png" style="margin-bottom: 10px; margin-top: 5px;" alt="Surface Cleaning"></div>
                <div class="card-body">
                  <h3 class="card-title">Tile & Surface Cleaning</h3>
                  <p class="card-text">Restore the shine to your pool's tiles and surfaces with our deep cleaning service.</p>
                </div>
              </div>
            </div>
          </div>
        </div>        
      </div>
    </div>
  </div>

  <footer class="container-fluid" id="gtco-footer">
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
                  <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
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
                  <li class="nav-item"><a class="nav-link" href="#">Pool Cleaning</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Chemical Balancing</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Filter Maintenance</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Opening & Closing</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Leak Detection</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Tile & Surface Cleaning</a></li>
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
  <div class="oval"><img class="card-img-top" src="images/foot.png" alt="Pool Cleaning"></div>
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
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="owl-carousel/owl.carousel.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
