<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php"); 
    exit;
}
require '../db.php';

$poolInfo = [];
$result = $conn->query("SELECT * FROM pool");

while ($row = $result->fetch_assoc()) {
  $poolInfo[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<!--Developed by Jackson-->

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Pools - Poole's Pools Admin Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" />
  <link rel="stylesheet" href="css/fontawesome.min.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
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
<body id="reportsPage" class="bg02">
  <div id="home" class="w-100 px-0 mx-0">
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
                        <a class="nav-link d-flex" href="login.php">
                            <i class="far fa-user mr-2 tm-logout-icon"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
      </div>
    </div>

    <div class="row tm-content-row tm-mt-big">
      <div class="col-xl-11 col-lg-12 tm-md-12 tm-sm-12 tm-col mx-auto">
        <div class="bg-white tm-block h-100">
          <div class="row mb-3">
            <div class="col-md-8 col-sm-12">
              <h2 class="tm-block-title d-inline-block">Pool Directory</h2>
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-content-end align-items-center">
              <input style="border-radius: 20px;" type="text" class="form-control" placeholder="Search pools..." id="poolSearchInput">
            </div>
          </div>

          <div class="table-responsive">
                        <table class="table table-hover table-striped tm-table-striped-even mt-3" id="poolsTable">
                            <thead>
                            <tr class="tm-bg-gray">
                                <th scope="col" class="text-center" style="border-top-left-radius: 20px;">Pool ID Number</th>
                                <th scope="col" class="text-center">Customer ID</th>
                                <th scope="col" class="text-center">Type</th>
                                <th scope="col" class="text-center">Water Type</th>
                                <th scope="col" class="text-center">Size</th>
                                <th scope="col" class="text-center">Pool Address</th>
                                <th scope="col" class="text-center">Last Serviced</th>
                                <th scope="col" class="text-center" style="border-top-right-radius: 20px;">Appointment IDs Associated</th>

                            </tr>
                            </thead>
                            <tbody class="border">
                            <?php
                              $query = $conn->query("
                                SELECT p.pool_id, p.customer_id, p.type, p.water_type, p.size, p.location, p.last_service_date,
                                      GROUP_CONCAT(DISTINCT a.appointment_id SEPARATOR ', ') AS appointment_ids
                                FROM pool p
                                LEFT JOIN customer c ON p.customer_id = c.customer_id
                                LEFT JOIN appointment a ON p.pool_id = a.pool_id
                                GROUP BY p.pool_id

                              ");

                          

                            while ($row = $query->fetch_assoc()):
                            ?>
                                <tr>
                                <td class="tm-product-name border-right"><?= htmlspecialchars($row['pool_id']) ?></td>
                                <td class="tm-product-name border-right"><?= htmlspecialchars($row['customer_id']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['type']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['water_type']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['size']) ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['location'] ?? 'None') ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['last_service_date'] ?? 'None') ?></td>
                                <td class="text-center border-right"><?= htmlspecialchars($row['appointment_ids'] ?? 'None') ?></td>


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
    const poolSearchInput = document.getElementById('poolSearchInput');
    const poolRows = document.querySelectorAll('#poolsTable tbody tr');

    poolSearchInput.addEventListener('input', function () {
      const searchVal = this.value.toLowerCase();
      poolRows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchVal) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
