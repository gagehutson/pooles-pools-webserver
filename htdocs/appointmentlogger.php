<?php
session_start();
require 'db.php';


$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

$customer_id = $_SESSION['user_id'] ?? null;
$type = $_POST['type'] ?? '';
$water_type = $_POST['water_type'] ?? '';
$length = $_POST['length'] ?? '';
$width  = $_POST['width'] ?? '';
$depth  = $_POST['depth'] ?? '';
$size = "{$length}x{$width}x{$depth} ft";

$street = $_POST['street'] ?? '';
$city  = $_POST['city'] ?? '';
$state  = $_POST['state'] ?? '';
$zip  = $_POST['zip'] ?? '';
$location = "$street, $city, $state $zip";

$last_service_date = $_POST['last_service_date'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$preferred_date = $_POST['preferred_date'] ?? '';
$preferred_time = $_POST['preferred_time'] ?? '';
$datetime = date('Y-m-d H:i:s', strtotime("$preferred_date $preferred_time"));
$notes = $_POST['notes'] ?? '';

$update = $conn->prepare("UPDATE customer SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE customer_id = ?");
$update->bind_param("ssssi", $firstname, $lastname, $email, $phone, $customer_id);
$update->execute();
$update->close();

$stmt1 = $conn->prepare("INSERT INTO pool (customer_id, type, water_type, size, location, last_service_date) VALUES (?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("isssss", $customer_id, $type, $water_type, $size, $location, $last_service_date);
$stmt1->execute();

$pool_id = $conn->insert_id;

$stmt1->close();

$technician_id = null;
$stmtTech = $conn->prepare("SELECT technician_id FROM technician WHERE specialty = ? LIMIT 1");
$stmtTech->bind_param("s", $service_type);
$stmtTech->execute();
$resultTech = $stmtTech->get_result();

if ($resultTech->num_rows > 0) {
    $rowTech = $resultTech->fetch_assoc();
    $technician_id = $rowTech['technician_id'];
}
$stmtTech->close();

$stmt2 = $conn->prepare("INSERT INTO appointment (pool_id, technician_id, service_date, service_type, notes) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("iisss", $pool_id, $technician_id, $datetime, $service_type, $notes);
$stmt2->execute();
$stmt2->close();


$conn->close();

header("Location: account.php");
exit;
?>

<!--Developed by Sebastian-->
