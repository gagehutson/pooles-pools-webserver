<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}
require '../db.php';

$technician_id = $_SESSION['user_id'];

$topProducts = $conn->query("
    SELECT i.item_name, SUM(o.quantity) AS total_sold 
    FROM orders o
    JOIN inventory i ON o.part_number = i.part_number
    GROUP BY i.item_name 
    ORDER BY total_sold DESC 
    LIMIT 4
");

$technicianGreet = $conn->prepare("
    SELECT firstname, lastname 
    FROM technician 
    WHERE technician_id = ?
");
$technicianGreet->bind_param("i", $technician_id);
$technicianGreet->execute();
$result = $technicianGreet->get_result();
$technician = $result->fetch_assoc();


$upcoming = $conn->prepare("SELECT service_date, service_type FROM appointment WHERE technician_id = ? AND service_date >= CURDATE() ORDER BY service_date ASC LIMIT 7");
$upcoming->bind_param("i", $technician_id);
$upcoming->execute();
$upcomingResults = $upcoming->get_result();

$serviceData = $conn->query("
SELECT a.service_type, COUNT(s.servicejob_id) AS count 
FROM servicejob s
JOIN appointment a ON s.appointment_id = a.appointment_id
GROUP BY a.service_type 
");
$barLabels = [];
$barData = [];
while ($row = $serviceData->fetch_assoc()) {
    $barLabels[] = $row['service_type'];
    $barData[] = $row['count'];
}


$calendarEvents = [];
$calendarQuery = $conn->prepare("
    SELECT appointment_id, service_type, service_date 
    FROM appointment 
    WHERE technician_id = ? AND service_date >= CURDATE()
");
$calendarQuery->bind_param("i", $technician_id);
$calendarQuery->execute();
$calendarResult = $calendarQuery->get_result();


while ($row = $calendarResult->fetch_assoc()) {
    $calendarEvents[] = [
        'title' => $row['service_type'],
        'start' => $row['service_date'],
        'url' => 'appointments.php#' . $row['appointment_id']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<!--Developed by Gage Hutson-->
<!--Developed by Jackson-->
<!--Developed by Sebastian-->
<!--Developed by Karli Newberry-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/adminstyles.css">

</head>
<style>
.btn-deepend {
  display: inline-block;
  background:rgb(58, 63, 85) !important;
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
  background: linear-gradient(270deg,rgb(45, 61, 58),rgb(58, 63, 85)) !important;
  color: #fff;
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(0, 146, 156, 0.4);
  cursor: pointer;
}


.dropdown-menu {
  border-radius: 15px;
}
</style>

<body id="reportsPage">
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
                                    <a class="nav-link active" href="#">Dashboard
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
            <div class="row tm-content-row tm-mt-big">

                <div class="col-lg-6 mb-4">
                    <div class="bg-white tm-block h-100">
                        <div class="row">
                            <div class="col-8">
                                <h2 class="tm-block-title d-inline-block"><?= htmlspecialchars('Hello, ' . $technician['firstname'] . ' ' . $technician['lastname'] . '!') ?></h2>
                            </div>
                            <div>
                            <p class="col-12 mt-2 text-muted" style="font-size: 1.1rem;">
                                Welcome to the Deep End, where your skills make waves.
                            </p>
                            <p class="col-12 " style="font-size: 1.1rem;">
                                Let's dive in!
                            </p>
                            
                            </div>
                            <div class="container mt-4">

                            <div class="row justify-content-center">
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='report_queries.php'">Reports & Queries</button>
                                </div>
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='products.php'">Store Inventory</button>
                                </div>
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='jobs.php'">Completed Jobs</button>
                                </div>
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='customer.php'">Customer Lookup</button>
                                </div>
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='pool.php'">Pool Lookup</button>
                                </div>
                                <div class="col-4 mb-3">
                                <button class="btn btn-deepend w-100" onclick="location.href='orders.php'">Orders</button>
                                </div>
                            </div>
                            </div>


                            <div class="col-12 text-right mt-5">

                            <a href="appointments.php" class="btn btn-deepend">
                                View Appointment Schedules <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </a>

                            </div>


                        </div>
                    </div>
                </div>
                

                <div class="col-lg-6 mb-4">
                    <div class="bg-white tm-block h-100">
                        <div class="row">
                            <div class="col-8">
                                <h2 class="tm-block-title d-inline-block">Top Products</h2>
                            </div>
                            <div class="col-4 text-right">
                                <a href="products.php" class="tm-link-black">View All</a>
                            </div>
                        </div>
                        <ol class="tm-list-group tm-list-group-alternate-color tm-list-group-pad-big">
                            <?php while($row = $topProducts->fetch_assoc()): ?>
                                <li class="tm-list-group-item">
                                    <?= htmlspecialchars($row['item_name']) ?> — <?= htmlspecialchars($row['total_sold']) ?> sold
                                </li>
                            <?php endwhile; ?>
                        </ol>
                    </div>
                </div>


                <div class="col-lg-12 mb-4">

                    <div class="bg-white tm-block h-30">
                        <h2 class="tm-block-title">Service Performance</h2>
                        <canvas id="barChart" style="height: 100px;"></canvas>

                    </div>


                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="bg-white tm-block h-100">
                                <h2 class="tm-block-title mb-4">Upcoming</h2>

                                <?php if (empty($calendarEvents)): ?>
                                    <p class="text-center mt-3 text-muted">No appointments to show.</p>
                                <?php endif; ?>

                                <ol class="tm-list-group tm-list-group-alternate-color tm-list-group-pad-big">
                                    <?php while($row = $upcomingResults->fetch_assoc()): ?>
                                        <li class="tm-list-group-item">
                                            <?= date("M d", strtotime($row['service_date'])) ?> — <?= htmlspecialchars($row['service_type']) ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ol>
                                <div class="row mt-4">

                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="bg-white tm-block h-100">
                                <h2 class="tm-block-title mb-4">Calendar Schedule</h2>
                                <div id="calendar" style="min-height: 520px;"></div>

                                <div class="row mt-4">
                                <div class="col-12 text-right mt-5">
                                <a href="appointments.php" class="btn btn-deepend">
                                View Appointment Schedules <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </a>
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
    <script src="js/moment.min.js"></script>
    <script src="js/utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tooplate-scripts.js"></script>
    <script>
            let ctxLine, ctxBar, optionsLine, optionsBar, configLine, configBar, lineChart, barChart;


        $(function () {
            updateChartOptions();
            drawLineChart();
            drawBarChart(); 
            drawCalendar();

            let resizeTimer;

            $(window).resize(function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    updateChartOptions();
                    updateLineChart();
                    updateBarChart();
                }, 250);
            });

        })

        const barChartLabels = <?= json_encode($barLabels) ?>;
    const barChartData = <?= json_encode($barData) ?>;
    const calendarEvents = <?= json_encode($calendarEvents ?: []) ?>;



    function drawBarChart() {
    const ctx = document.getElementById("barChart").getContext("2d");

    const colors = [
        '#4e73df', // Blue
        '#1cc88a', // Green
        '#36b9cc', // Cyan
        '#f6c23e', // Yellow
        '#e74a3b', // Red
        '#858796', // Gray
        '#5a5c69', // Dark
        '#20c997'  // Teal
    ];

    const backgroundColors = barChartLabels.map((_, index) => colors[index % colors.length]);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: barChartLabels,
            datasets: [{
                label: 'Service Jobs',
                data: barChartData,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false 
                }
            }
        }
    });
}


    function drawCalendar() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: calendarEvents,
        eventColor: '#4e73df',
        eventTextColor: '#4e73df',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        eventClick: function(info) {
            if (info.event.url) {
                window.open(info.event.url, '_blank');
                info.jsEvent.preventDefault();
            }
        }
    });
    calendar.render();
}

    </script>
</body>
</html>