<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}
require '../db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $part_number = $_POST['partnumber'] ?? '';
    $name = $_POST['itemname'] ?? '';
    $description = $_POST['description'] ?? '';
    $costper = $_POST['costperunit'] ?? 0;
    $stock = $_POST['totalquantity'] ?? 0;
    $image = $_POST['imagepath'] ?? '';

    if (!empty($part_number) && !empty($name) && !empty($description) && is_numeric($costper) && is_numeric($stock) && !empty($image)) {
        $checkStmt = $conn->prepare("SELECT part_number FROM inventory WHERE part_number = ?");
        $checkStmt->bind_param("s", $part_number);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $updateStmt = $conn->prepare("UPDATE inventory SET item_name = ?, total_quantity = ?, cost_per_unit = ?, description = ?, image_path = ? WHERE part_number = ?");
            $updateStmt->bind_param("sdisss", $name, $stock, $costper, $description, $image, $part_number);

            if ($updateStmt->execute()) {
                echo "<script>alert('Product updated successfully.');</script>";
            } else {
                echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
            }
            $updateStmt->close();
        } else {
            echo "<script>alert('Error: Product with part number $part_number does not exist.');</script>";
        }

        $checkStmt->close();
    } else {
        echo "<script>alert('Please fill out all fields correctly.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<!--Developed by Gage Hutson-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
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
<body class="bg02">
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
                                    <a class="dropdown-item" href="../index.php">Customer Page</a>
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
        <div class="row tm-mt-big justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                        </div>
                    </div>
                    <div class="row mt-4 tm-edit-product-row">
                        <div class="col-xl-7 col-lg-7 col-md-12">
                        <form action="edit-product.php" method="POST" class="tm-edit-product-form">

                                <div class="input-group mb-3">
                                    <label for="partnumber" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Part Number</label>
                                    <input id="partnumber" placeholder="Input existing part #" name="partnumber" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>

                                <div class="input-group mb-3">
                                    <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Product
                                        Name
                                    </label>
                                    <input id="name" placeholder="Item Name" name="itemname" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                                </div>
                                <div class="input-group mb-3">
                                    <label for="description" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 mb-2">Description</label>
                                    <textarea name="description"  class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" rows="3" required></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="costper" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Cost Per Unit
                                    </label>
                                    <input id="costper" placeholder="Cost per item" name="costperunit" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7"
                                        data-large-mode="true">
                                </div>
                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Total Quantity
                                    </label>
                                    <input id="stock" name="totalquantity" placeholder="How much in stock" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Image Name
                                    </label>
                                    <input id="stock" name="imagepath" placeholder="example.jpg" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <button type="submit" class="btn btn-deepend">Update Item
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="jquery-ui-datepicker/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(function () {
            $('#expire_date').datepicker();
        });
    </script>
</body>

</html>