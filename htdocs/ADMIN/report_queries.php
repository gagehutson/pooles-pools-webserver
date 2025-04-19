<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}

$result = null;
$reportName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report'])) {
    $reportName = $_POST['report'];

    switch ($reportName) {
        case 'report1':
            $sql = "SELECT service_type, COUNT(*) AS total_requested 
                    FROM appointment 
                    GROUP BY service_type 
                    ORDER BY total_requested DESC";
            break;
        case 'report2':
            $sql = "SELECT t.firstname, t.lastname, COUNT(a.appointment_id) AS appointments_handled 
                    FROM technician t
                    JOIN appointment a ON a.technician_id = t.technician_id
                    WHERE a.service_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                    GROUP BY t.technician_id
                    ORDER BY appointments_handled DESC";
            break;
        case 'report3':
            $sql = "SELECT i.item_name, SUM(o.quantity) AS total_used 
                    FROM orders o
                    JOIN inventory i ON o.part_number = i.part_number
                    GROUP BY i.item_name
                    ORDER BY total_used DESC
                    LIMIT 5";
            break;
        case 'query1':
            $sql = "SELECT a.appointment_id, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, a.service_type, a.service_date
                    FROM appointment a
                    JOIN pool p ON a.pool_id = p.pool_id
                    JOIN customer c ON p.customer_id = c.customer_id
                    ORDER BY a.service_date DESC";
            break;
        case 'query2':
            $sql = "SELECT appointment_id, service_type, service_date, notes
                    FROM appointment
                    WHERE technician_id = {$_SESSION['user_id']} AND service_date >= CURDATE()
                    ORDER BY service_date ASC";
            break;
        case 'query3':
            $sql = "SELECT appointment_id, service_date, notes
                    FROM appointment
                    WHERE service_type = 'Pool Cleaning'
                    ORDER BY service_date DESC";
            break;
        default:
            $sql = '';
    }

    if (!empty($sql)) {
        $result = $conn->query($sql);
    }
}
?>

<!--Developed by Sebastian-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports & Queries | Poole's Pools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/adminstyles.css">

    <style>
        body {
            background-color: #f5f5f5;
        }

        .btn-deepend {
            display: inline-block;
            background: rgb(58, 63, 85) !important;
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
            background: linear-gradient(270deg, rgb(45, 61, 58), rgb(58, 63, 85)) !important;
            color: #fff;
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 146, 156, 0.4);
            cursor: pointer;
        }
    </style>
</head>

<body>

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


<div class="container py-5">
<h2 class="tm-block-title text-center" style="padding-bottom: 40px; font-size: 43px;">
  Reports & Queries Dashboard
</h2>


    <form method="POST" class="mb-5">
        <div class="row">
            <?php
            $buttons = [
                'report1' => 'Most Requested Services',
                'report2' => 'Technician Service Jobs Completed (30 Days)',
                'report3' => 'Top Ordered Products',
                'query1'  => 'All Appointments Ordered by Customer',
                'query2'  => 'Upcoming Services for You',
                'query3'  => 'Pool Cleaning Appointments',
            ];
            foreach ($buttons as $value => $label): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <button type="submit" name="report" value="<?= $value ?>" class="btn btn-deepend w-100"><?= $label ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="card">
            <div class="card-header bg-info text-white">
                <?= strpos($reportName, 'report') !== false ? 'Report' : 'Query' ?>: <?= $buttons[$reportName] ?>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <?php while ($field = $result->fetch_field()): ?>
                                <th><?= htmlspecialchars($field->name) ?></th>
                            <?php endwhile; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $result->data_seek(0); ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($reportName): ?>
        <div class="alert alert-warning mt-4">No data found for <?= $buttons[$reportName] ?>.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
