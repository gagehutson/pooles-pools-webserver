<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../index.php");
    exit;
}

require '../db.php';

$appointment_id = $_POST['appointment_id'] ?? null;
$hours_worked = $_POST['hours_worked'] ?? null;
$job_description = $_POST['job_description'] ?? '';
$date_completed = $_POST['date_completed'] ?? null;

if (!$appointment_id || !$hours_worked || !$date_completed) {
    die("Missing required data.");
}

$stmt = $conn->prepare("
    SELECT t.salary
    FROM appointment a
    JOIN technician t ON a.technician_id = t.technician_id
    WHERE a.appointment_id = ?
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No technician found for this appointment.");
}

$tech = $result->fetch_assoc();
$salary = $tech['salary'];
$base_service_fee = 50;
$service_cost = ($hours_worked * $salary) + $base_service_fee;

$used_inventory = $_POST['used_inventory'] ?? 'no';

$stmt = $conn->prepare("
    INSERT INTO servicejob (appointment_id, service_cost, job_description, hours_worked, date_completed)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("idsds", $appointment_id, $service_cost, $job_description, $hours_worked, $date_completed);

if ($stmt->execute()) {
    if ($used_inventory === 'yes') {
        header("Location: products.php?appointment_id=$appointment_id");
        exit;
    } else {
        header("Location: appointments.php?success=1");
        exit;
    }
    exit;
} else {
    echo "Error inserting into servicejob: " . $stmt->error;
}

?>

<!--Developed by Jackson-->


