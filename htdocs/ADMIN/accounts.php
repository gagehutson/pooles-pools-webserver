<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}
require '../db.php';


$tech_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['phone'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password === $password2) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE technician SET firstname=?, address=?, phone=?, password=? WHERE technician_id=?");
        $stmt->bind_param("ssssi", $name, $address, $email, $hashedPassword, $tech_id);
        $stmt->execute();
    } else {
        echo "<script>alert('Passwords do not match!');</script>";
    }
}

$stmt = $conn->prepare("SELECT firstname, address, phone FROM technician WHERE technician_id = ?");
$stmt->bind_param("i", $tech_id);
$stmt->execute();
$technician = $stmt->get_result()->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
    
<!--Developed by Gage Hutson-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accounts Page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/adminstyles.css">
</head>
<style>
.btn-deepend {
  display: inline-block;
  background:rgb(82, 103, 209) !important;
  color: white !important;
  text-decoration: none !important;
  border: none !important;
  border-radius: 15px !important;
  padding: 10px 20px !important;
  font-weight: bold;
  font-size: 14px;
  transition: background 0.3s ease;
  width: 120px;
}

.btn-deepend:hover {
  background: linear-gradient(270deg,rgb(19, 107, 92),rgb(82, 103, 209)) !important; 
  color: #fff;
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(0, 146, 156, 0.4);
  cursor: pointer;
}
.dropdown-menu {
  border-radius: 15px;
}
</style>
<body class="bg03">
    <div class="w-100 px-0 mx-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-xl navbar-light bg-light">
                    <a class="navbar-brand d-flex flex-column align-items-center text-center" href="adminindex.php">
                        <h1 class="tm-site-title mb-0">The Deep End</h1>
                        <span class="tm-subtitle">Poole's Pools Admin Dashboard</span>
                    </a>                                         
                    <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="adminindex.php">Dashboard
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Schedule
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="appointments.php">Appointments</a>
                                    <a class="dropdown-item" href="jobs.php">Service Jobs</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Products
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="products.php">Store Inventory</a>
                                    <a class="dropdown-item" href="productcheckout.php">Service Checkout</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Customer Data
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="customer.php">Customers</a>
                                    <a class="dropdown-item" href="pool.php">Pools</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Admin
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="accounts.php">Account</a>
                                    <a class="dropdown-item" href="billing.php">Billing</a>
                                    <a class="dropdown-item" href="orders.php">Orders</a>
                                    <a class="dropdown-item" href="../index.php">Customer View Page</a>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="../logout.php">
                                    <i class="far fa-user mr-2 tm-logout-icon"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="row tm-content-row tm-mt-big">
                <div class="col-md-9 col-lg-10 mx-auto">
                  <div class="bg-white tm-block h-100">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <h2 class="tm-block-title" style="margin-bottom: 45px; margin-top: 5px;">Edit Account Settings</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                        <form action="" method="POST" class="tm-signup-form">
                            <div class="form-group">
                                <label for="name"><strong>User Name</strong></label>
                                <input style="border-radius: 15px;" placeholder="User Name" id="name" name="name" type="text" class="form-control validate" value="<?= htmlspecialchars($technician['firstname'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="email"><strong>Account Email</strong></label>
                                <input style="border-radius: 15px;" placeholder="Account Email" id="email" name="email" type="email" class="form-control validate" value="<?= htmlspecialchars($technician['phone'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone"><strong>Address</strong></label>
                                <input style="border-radius: 15px;" placeholder="Address" id="phone" name="phone" type="tel" class="form-control validate" value="<?= htmlspecialchars($technician['address'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="password"><strong>Password</strong></label>
                                <input style="border-radius: 15px;" placeholder="******" id="password" name="password" type="password" class="form-control validate">
                            </div>
                            <div class="form-group">
                                <label for="password2"><strong>Re-enter Password</strong></label>
                                <input style="border-radius: 15px;" placeholder="******" id="password2" name="password2" type="password" class="form-control validate">
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <button  type="submit" class="btn btn-deepend">Update</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-8 col-sm-12">
                                <h2 class="tm-block-title d-inline-block" style="margin-top: 100px;">Account Info</h2>
                        </div>
                        </div>
                        
                    </div>
                    <div class="row tm-content-row" >
                        <div class="col-xl-12 col-lg-12 tm-md-12">
                          <div class="bg-white tm-block h-100">
                  
                            <div class="table-responsive">
                        <table class="table table-hover" id="poolsTable" >
                            <thead style="border: none;">
                            <tr class="tm-bg-gray" >
                                <th scope="col" class="text-center" style="border-top-left-radius: 20px;">Tech ID Number</th>
                                <th scope="col" class="text-center">Tech Name</th>
                                <th scope="col" class="text-center">Hourly Rate</th>
                                <th scope="col" class="text-center">Address</th>
                                <th scope="col" class="text-center">Phone #</th>
                                <th scope="col" class="text-center">Emergency #</th>
                                <th scope="col" class="text-center" style="border-top-right-radius: 20px;">DOB</th>

                            </tr>
                            </thead>
                            <tbody class="border">
                            <?php
                                $tech_id = $_SESSION['user_id'];

                                $query = $conn->prepare("
                                    SELECT technician_id, firstname, lastname, salary, address, phone, emergency_phone, dob
                                    FROM technician
                                    WHERE technician_id = ?
                                ");
                                $query->bind_param("i", $tech_id);
                                $query->execute();
                                $result = $query->get_result();

                                if ($row = $result->fetch_assoc()):

                            ?>
                                <tr>
                                <td class="tm-product-name border-right"><?= htmlspecialchars($row['technician_id']) ?></td>
                                <td class="tm-product-name border-right"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['salary']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['address']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['phone']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['emergency_phone']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['dob'] ?? 'None') ?></td>


                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                  
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>