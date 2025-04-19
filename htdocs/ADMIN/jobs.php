<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php"); 
    exit;
}

require '../db.php'; 

$stmt = $conn->prepare("
    SELECT  s.servicejob_id, s.service_cost, s.job_description, s.hours_worked, s.date_completed,
            a.appointment_id, a.service_type, a.service_date, a.notes, 
            c.firstname, c.lastname,
            p.location, 
            t.firstname AS tech_fname, t.lastname AS tech_lname
    FROM servicejob s
    JOIN appointment a ON s.appointment_id = a.appointment_id
    JOIN pool p ON a.pool_id = p.pool_id
    JOIN technician t ON a.technician_id = t.technician_id
    JOIN customer c ON p.customer_id = c.customer_id
");

$stmt->execute();
$servicejob = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<!--Developed by Gage Hutson-->
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Completed Jobs - Poole's Pools Admin Dashboard</title>
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

    <div class="row tm-content-row tm-mt-big">
              <div class="col-xl-11 col-lg-12 tm-md-12 tm-sm-12 tm-col mx-auto">
                <div class="bg-white tm-block h-100">
                  <div class="row mb-3">
                      <div class="col-md-8 col-sm-12">
                          <h2 class="tm-block-title d-inline-block" style="padding-bottom: 40px; font-size: 43px;">Completed Jobs</h2>
                        </div>
                    <div class="col-md-8 col-sm-12">
                      <input type="text" class="form-control" placeholder="Search appointments..." id="searchInput" style="border-radius: 15px;">
                    </div>
                    <div class="col-md-4 col-sm-12">
                    <?php
                      $techStmt = $conn->prepare("SELECT DISTINCT t.technician_id, t.firstname, t.lastname, t.specialty FROM technician t");
                      $techStmt->execute();
                      $technicians = $techStmt->get_result();
                      ?>

                      <select class="form-control" id="jobTechFilter" style="line-height: 2.5; padding-top: .85rem; padding-bottom: .85rem; height: auto; border-radius: 15px;">
                        <option value="">All Technicians</option>
                        <?php while ($tech = $technicians->fetch_assoc()): ?>
                          <option value="<?= htmlspecialchars($tech['firstname'] . ' ' . $tech['lastname'] . ' - ' . $tech['specialty']) ?>">
                            <?= htmlspecialchars($tech['firstname'] . ' ' . $tech['lastname']  . ' - ' . $tech['specialty']) ?>
                          </option>
                        <?php endwhile; ?>
                      </select>

                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-hover table-striped tm-table-striped-even mt-3" id="appointmentsTable">
                      <thead>
                        <tr class="tm-bg-gray">
                          <th class="text-center" style="border-top-left-radius: 20px;">Service Job ID</th>
                          <th class="text-center">Appt ID</th>
                          <th class="text-center">Customer</th>
                          <th class="text-center">Pool Address</th> 
                          <th class="text-center">Technician</th>
                          <th class="text-center">Service Cost</th>
                          <th class="text-center">Hours Worked</th>
                          <th class="text-center">Completed</th>
                          <th class="text-center" style="border-top-right-radius: 20px;">Job Notes</th>
                        </tr>
                      </thead>
                      <tbody class="border">
                        <?php while ($row = $servicejob->fetch_assoc()): ?>
                          <tr>
                            <td class="border-right"><?= htmlspecialchars($row['servicejob_id']) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['appointment_id']) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['location']) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['tech_fname'] . ' ' . $row['tech_lname']) ?></td>
                            <td class="border-right">$ <?= htmlspecialchars(number_format($row['service_cost'], 2)) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['hours_worked']) ?></td>
                            <td class="border-right"><?= htmlspecialchars(date('Y-m-d h:i A', strtotime($row['date_completed']))) ?></td>
                            <td class="border-right"><?= htmlspecialchars($row['job_description'] ?? 'N/A') ?></td>
                          </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    const jobSearch = document.getElementById('jobSearchInput');
    const jobTech = document.getElementById('jobTechFilter');
    const jobRows = document.querySelectorAll('#jobsTable tbody tr');
  
    function filterJobRows() {
      const searchVal = jobSearch.value.toLowerCase();
      const techVal = jobTech.value.toLowerCase();
      jobRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const tech = row.cells[5].textContent.toLowerCase();
        const matchesSearch = text.includes(searchVal);
        const matchesTech = !techVal || tech === techVal;
        row.style.display = matchesSearch && matchesTech ? '' : 'none';
      });
    }
  
    jobSearch.addEventListener('input', filterJobRows);
    jobTech.addEventListener('change', filterJobRows);
  </script>
</body>

</html>
