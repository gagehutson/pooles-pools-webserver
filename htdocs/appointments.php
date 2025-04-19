<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // or login page
    exit;
}
require 'db.php';

$customer_id = $_SESSION['user_id'] ?? null;
$customer = ['firstname' => '', 'lastname' => '', 'email' => '', 'phone' => ''];

if ($customer_id) {
  $stmt = $conn->prepare("SELECT firstname, lastname, email, phone FROM customer WHERE customer_id = ?");
  $stmt->bind_param("i", $customer_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 1) {
    $customer = $result->fetch_assoc();
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Appointment Form</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="owl-carousel/assets/owl.carousel.min.css" type="text/css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  
<!--Developed by Gage Hutson-->

  <style>
    .form-section { display: none; }
    .form-section.active { display: block; }
    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }
    .step {
      flex: 1;
      text-align: center;
      padding: 0.5rem;
      background-color: #eee;
      border-radius: 0.5rem;
    }
    .step.active {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
    }

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

  <div class="container mt-5">
    <h2 class="text-center mb-4" style="padding-bottom: 40px;">Schedule an Appointment</h2>

    <div class="step-indicator">
      <div class="step active" id="step-0">Customer Info</div>
      <div class="step" id="step-1">Pool Details</div>
      <div class="step" id="step-2">Service Details</div>
      <div class="step" id="step-3">Finalize</div>
    </div>

    <form action="appointmentlogger.php" method="post">
      <div class="form-section active">
      <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" id="firstName" name="firstname" required 
                value="<?= htmlspecialchars($customer['firstname']) ?>" 
                placeholder="Enter your first name">
        </div>

        <div class="form-group">
          <label for="lastName">Last Name</label>
          <input type="text" class="form-control" id="lastName" name="lastname" required 
                value="<?= htmlspecialchars($customer['lastname']) ?>" 
                placeholder="Enter your last name">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" required 
                value="<?= htmlspecialchars($customer['email']) ?>" 
                placeholder="you@example.com">
        </div>

        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="tel" class="form-control" id="phone" name="phone" required 
                value="<?= htmlspecialchars($customer['phone']) ?>" 
                placeholder="(555) 123-4567">
        </div>
        </div>

      <div class="form-section">

      <div class="form-group">
          <label for="pool_type">Pool Type</label>
          <select class="form-control" id="type" name="type" required>
            <option value="" disabled selected>Select pool type</option>
            <option value="Inground">Inground</option>
            <option value="Above Ground">Above Ground</option>
          </select>
        </div>

        <div class="form-group">
          <label for="water_type">Water Type</label>
          <select class="form-control" id="water_type" name="water_type" required>
            <option value="" disabled selected>Select water type</option>
            <option value="Chlorine">Chlorine</option>
            <option value="Saltwater">Saltwater</option>
            <option value="Mineral">Mineral</option>
            <option value="Bromine">Bromine</option>
          </select>
        </div>

        <div class="form-group">
          <label for="poolSize">Pool Size</label>
          <div class="d-flex gap-2">
            <input type="number" class="form-control mr-2" placeholder="Length (ft)" name="length" required>
            <input type="number" class="form-control mr-2" placeholder="Width (ft)" name="width" required>
            <input type="number" class="form-control" placeholder="Depth (ft)" name="depth" required>
          </div>
        </div>

        <div class="form-row d-flex align-items-end">
          <div class="form-group col-md-4">
            <label for="city">Street</label>
            <input type="text" class="form-control" id="street" name="street" required placeholder="Street Address">
          </div>
          <div class="form-group col-md-2">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required placeholder="City Name">
          </div>
          <div class="form-group col-md-4 align-self-end">
            <label for="state">State</label>
            <select style="margin-bottom: 8px" id="state" name="state" class="form-control" required>
              <option value="" selected disabled>Select...</option>
              <option value="TX">TX</option>
            </select>
          </div>

          <div class="form-group col-md-2">
            <label for="zip">Zip</label>
            <input type="text" class="form-control" id="zip" name="zip" required placeholder="Zip Code">
          </div>
        </div>


        <div class="form-group">
          <label for="poolType">Last Serviced (Leave this field blank if never serviced)</label>
          <input type="date" class="form-control" id="last_service_date" name="last_service_date"> 
        </div>
      </div>

      <div class="form-section">
      <div class="form-group">
        <label for="serviceType">Service Type</label>
        <select class="form-control" id="service_type" name="service_type" required>
          <option value="" disabled selected>Select a service</option>
          <option value="Pool Cleaning">Pool Cleaning</option>
          <option value="Chemical Balancing">Chemical Balancing</option>
          <option value="Filter Maintenance">Filter Maintenance</option>
          <option value="Opening & Closing">Opening & Closing</option>
          <option value="Leak Detection">Leak Detection</option>
          <option value="Tile & Surface Cleaning">Tile & Surface Cleaning</option>
        </select>
      </div>

        
      <div class="form-group">
        <label for="preferredDate">Preferred Service Date</label>
        <input type="date" class="form-control" id="preferred_date" name="preferred_date" min="">
      </div>

      <div class="form-group">
        <label for="preferredTime">Preferred Service Time</label>
        <input type="time" class="form-control" id="preferred_time" name="preferred_time" min="08:00" max="17:00">
      </div>


        <div class="form-group">
          <label for="notes">Additional Notes</label>
          <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Let us know any specific details..."></textarea>
        </div>
      </div>

      <div class="form-section">
        <p>Please review your information before submitting.</p>
        <button type="submit" class="btn btn-success" o>Submit</button>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-secondary" id="prevBtn">Back</button>
        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
      </div>
    </form>
  </div>

  <script>
    const sections = document.querySelectorAll('.form-section');
    const steps = document.querySelectorAll('.step');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    let currentStep = 0;

    function updateForm() {
      sections.forEach((section, index) => {
        section.classList.toggle('active', index === currentStep);
        steps[index].classList.toggle('active', index === currentStep);
      });

      prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-block';
      nextBtn.style.display = currentStep === sections.length - 1 ? 'none' : 'inline-block';
    }

    nextBtn.addEventListener('click', () => {
      if (currentStep < sections.length - 1) {
        currentStep++;
        updateForm();
      }
    });

    prevBtn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        updateForm();
      }
    });

    updateForm();

    document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('preferred_date').setAttribute('min', today);
  });
  </script>
</body>
</html>
