<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'technician') {
  header("Location: /ADMIN/accounts.php");
  exit;
}
elseif(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php"); 
    exit;
}


require 'db.php';

$userID = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT a.appointment_id, a.service_type, a.service_date, a.notes 
    FROM appointment a
    JOIN pool p ON a.pool_id = p.pool_id
    WHERE p.customer_id = ?
");

$stmt->bind_param("i", $userID);
$stmt->execute();
$appointment = $stmt->get_result();


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
$orderStmt = $conn->prepare("
  SELECT o.order_id, o.part_number, o.quantity, o.total_price, o.order_date, i.item_name
  FROM orders o
  JOIN inventory i ON o.part_number = i.part_number
  WHERE o.customer_id = ?
  ORDER BY o.order_date DESC
");

$orderStmt->bind_param("i", $userID);
$orderStmt->execute();
$orders = $orderStmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Account Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <!--Developed by Gage Hutson-->
  
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
    }
    .settings-form {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .settings-form h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 800;
      background: linear-gradient(270deg, #1d3ede, #00929c);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: smoothGradient 8s ease infinite;
    }
    .settings-form input[type="text"],
    .settings-form input[type="email"],
    .settings-form input[type="password"],
    .settings-form input[type="tel"] {
      border-radius: 10px;
      margin-bottom: 15px;
      color: #000;
    }
    .settings-form button:hover {
      opacity: 0.95;
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
      

  <div class="settings-form">
    <h2 class="shawty">Account Settings</h2>
    <form method="POST" action="update_account.php">


    <div class="form-group">
          <label for="firstName"><b>First Name</b></label>
          <input type="text" class="form-control" id="firstName" name="firstname" required 
                value="<?= htmlspecialchars($customer['firstname']) ?>" 
                placeholder="Enter your first name">
        </div>

        <div class="form-group">
          <label for="lastName"><b>Last Name</b></label>
          <input type="text" class="form-control" id="lastName" name="lastname" required 
                value="<?= htmlspecialchars($customer['lastname']) ?>" 
                placeholder="Enter your last name">
        </div>

        <div class="form-group">
          <label for="email"><b>Email</b></label>
          <input type="email" class="form-control" id="email" name="email" required 
                value="<?= htmlspecialchars($customer['email']) ?>" 
                placeholder="you@example.com">
        </div>

        <div class="form-group">
          <label for="phone"><b>Phone</b></label>
          <input type="tel" class="form-control" id="phone" name="phone" required 
                value="<?= htmlspecialchars($customer['phone']) ?>" 
                placeholder="(555) 123-4567">
        </div>
        <div class="form-group">
            <label for="currentpassword"><b>Current Password</b></label>
            <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Input current password to add new password">
          </div>

          <div class="form-group">
            <label for="password"><b>New Password</b></label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Leave blank to keep current password">
          </div>

          <div class="form-group">
            <label for="confirmPassword"><b>Confirm Password</b></label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
          </div>
      <button type="submit" class="btn-any">Update Account</button>

        </div>


    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
