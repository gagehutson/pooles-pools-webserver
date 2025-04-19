<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'technician') {
  header("Location: /ADMIN/accounts.php"); // redirect if not logged in as customer
  exit;
}
elseif(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php"); // redirect if not logged in as customer
    exit;
}


require 'db.php'; // include your DB connection

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
    .settings-form button {
      background: -webkit-linear-gradient(left, #1d3ede, #00929c);
      color: #fff;
      border: none;
      padding: 12px 20px;
      border-radius: 10px;
      cursor: pointer;
      display: block;
      width: 100%;
      margin-top: 20px;
    }
    .settings-form button:hover {
      opacity: 0.95;
    }
    @keyframes smoothGradient {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
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
      

<div class="settings-form mt-5">
<h2 class="shawty">Your Order History</h2>
<?php if ($orders->num_rows > 0): ?>
    <?php while ($order = $orders->fetch_assoc()): ?>
      <div class="mb-4 p-3 border rounded shadow-sm" style="background-color: #f9f9f9;">
        <div class="form-group">
          <label><strong>Order ID:</strong></label>
          <p><?= htmlspecialchars($order['order_id']) ?></p>
        </div>

        <div class="form-group">
          <label><strong>Item Name:</strong></label>
          <p><?= htmlspecialchars($order['item_name']) ?></p>
        </div>

        <div class="form-group">
          <label><strong>Part Number:</strong></label>
          <p><?= htmlspecialchars($order['part_number']) ?></p>
        </div>

        <div class="form-group">
          <label><strong>Quantity:</strong></label>
          <p><?= htmlspecialchars($order['quantity']) ?></p>
        </div>

        <div class="form-group">
          <label><strong>Total Price:</strong></label>
          <p>$<?= number_format($order['total_price'], 2) ?></p>
        </div>

        <div class="form-group">
          <label><strong>Order Date:</strong></label>
          <p><?= date("F j, Y, g:i a", strtotime($order['order_date'])) ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>You haven’t placed any orders yet.</p>
  <?php endif; ?>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
