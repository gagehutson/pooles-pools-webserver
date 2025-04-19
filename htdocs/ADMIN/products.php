<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}
require '../db.php';

$incomingAppointmentId = $_GET['appointment_id'] ?? '';
$linkedServiceJobId = '';

if (!empty($incomingAppointmentId)) {
    $stmt = $conn->prepare("SELECT servicejob_id FROM servicejob WHERE appointment_id = ?");
    $stmt->bind_param("i", $incomingAppointmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $linkedServiceJobId = $row['servicejob_id'];
    }
    $stmt->close();
}

$inventoryItems = [];
$result = $conn->query("SELECT * FROM inventory");

while ($row = $result->fetch_assoc()) {
    $inventoryItems[] = $row;
}


if (isset($_GET['deleted'])) {
    echo "<script>alert('Item deleted successfully.');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<!--Developed by Gage Hutson-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products Page</title>
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

  margin-bottom: 40px;
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
<body id="reportsPage" class="bg02">
    <div class="" id="home">
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

            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <form action="service_inventory_table_update.php" method="POST" class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout Inventory Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="serviceJobID">Service Job ID</label>
                        <input type="number" class="form-control" name="servicejob_id" style="border-radius: 10px;" placeholder="Service Job ID" required
                        value="<?= htmlspecialchars($linkedServiceJobId) ?>" <?= $linkedServiceJobId ? 'readonly' : '' ?>>


                    </div>
            
                    <div class="form-group">
                        <label for="partNumber">Part Number</label>
                        <input type="number" class="form-control" name="part_number" required placeholder="Part Number" style="border-radius: 10px;">
                    </div>
            
                    <div class="form-group">
                        <label for="serviceJobID">Technician ID</label>
                        <input type="number" class="form-control" name="technician_id" required placeholder="Technician ID Number" style="border-radius: 10px;">
                    </div>

                    <div class="form-group">
                        <label for="checkoutDate">Checkout Date</label>
                        <input type="date" class="form-control" name="checkout_date" required placeholder="Date" style="border-radius: 10px;">
                    </div>
            
                    <div class="form-group">
                        <label for="quantityUsed">Quantity Used</label>
                        <input type="number" class="form-control" name="quantity_used" required placeholder="Number Used" style="border-radius: 10px;">
                    </div>
                    </div>
                    
                    <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary" style="border-radius: 10px; background-color:rgb(127, 181, 226); color: black;">Submit Checkout</button >
                    </div>
                </form>
                </div>
            </div>
  
            <div class="row tm-content-row tm-mt-big">
                <div class="col-xl-11 col-lg-12 tm-md-12 tm-sm-12 tm-col mx-auto">
                  <div class="bg-white tm-block h-100">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                          <h2 class="tm-block-title d-inline-block">Store Inventory</h2>
                        </div>
                        <div class="col-md-4 col-sm-12 d-flex justify-content-end align-items-center button-group">
                        <a href="#" id="checkoutButton" class="btn btn-deepend mr-3">Checkout Item</a>
                            <a href="add-product.php" class="btn btn-deepend mr-3">Add New Item</a>
                            <a href="edit-product.php" class="btn btn-deepend mr-3">Edit Item</a>
                        </div>
                          
                      </div>
                      
                        <div class="table-responsive">
                        <table class="table table-hover table-striped tm-table-striped-even mt-3">
                            <thead>
                            <tr class="tm-bg-gray">
                                <th scope="col" class="text-center" style="border-top-left-radius: 20px;">Product Name</th>
                                <th scope="col" class="text-center">Part Number</th>
                                <th scope="col" class="text-center">Total Quantity</th>
                                <th scope="col" class="text-center">Cost Per Unit</th>
                                <th scope="col" class="text-center" style="border-top-right-radius: 20px;">Description</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody class="border">
                            <?php
                            $query = $conn->query("SELECT * FROM inventory");

                            while ($row = $query->fetch_assoc()):
                            ?>
                                <tr>
                                <td class="tm-product-name border-right"><?= htmlspecialchars($row['item_name']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['part_number']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['total_quantity']) ?></td>
                                <td class="text-center border-right">$<?= number_format($row['cost_per_unit'], 2) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['description']) ?></td>
                                <td class="text-center border-right">
                                    <a href="delete_product.php?part_number=<?= urlencode($row['part_number']) ?>" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="fas fa-trash-alt tm-trash-icon"></i>
                                    </a>
                                </td>

                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>

    $(function () {
        $('.tm-product-name').on('click', function () {
            window.location.href = "edit-product.php";
        });

        const autoTriggerModal = <?= $linkedServiceJobId ? 'true' : 'false' ?>;
        if (autoTriggerModal) {
            $('#checkoutModal').modal('show');
        }
    });


    document.getElementById('checkoutButton').addEventListener('click', function () {
        $('#checkoutModal').modal('show');
    });

    document.getElementById('submit').addEventListener('click', function () {
        alert("Item Checked Out!");
    });

    const validPartNumbers = <?= json_encode(array_column($inventoryItems, 'part_number')) ?>;

    document.querySelector('form').addEventListener('submit', function (e) {
        const jobId = document.querySelector('[name="servicejob_id"]').value.trim();
        const partNumber = document.querySelector('[name="part_number"]').value.trim();
        const date = document.querySelector('[name="checkout_date"]').value.trim();
        const qty = document.querySelector('[name="quantity_used"]').value.trim();

        if (!jobId || !partNumber || !date || !qty) {
            e.preventDefault();
            alert('Please fill in all fields before submitting.');
            return;
        }

        if (!validPartNumbers.includes(Number(partNumber))) {
            e.preventDefault();
            alert('Invalid Part Number. Please enter a valid part number from the inventory.');
        }
    });
</script>

</body>

</html>