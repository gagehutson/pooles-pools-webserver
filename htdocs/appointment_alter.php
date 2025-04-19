<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit;
}

$customer_id = $_SESSION['user_id'];
$appointment_id = $_POST['appointment_id'] ?? null;
$action = $_POST['action'] ?? '';

if ($appointment_id && $action === 'update') {
    $new_date = $_POST['service_date'] ?? '';
    $new_notes = $_POST['notes'] ?? '';

    $stmt = $conn->prepare("
        UPDATE appointment 
        SET service_date = ?, notes = ? 
        WHERE appointment_id = ? 
        AND pool_id IN (SELECT pool_id FROM Pool WHERE customer_id = ?)
    ");
    $stmt->bind_param("ssii", $new_date, $new_notes, $appointment_id, $customer_id);
    $stmt->execute();
} elseif ($appointment_id && $action === 'cancel') {
    $stmt = $conn->prepare("
        DELETE FROM appointment 
        WHERE appointment_id = ? 
        AND pool_id IN (SELECT pool_id FROM Pool WHERE customer_id = ?)
    ");
    $stmt->bind_param("ii", $appointment_id, $customer_id);
    $stmt->execute();
}

header("Location: account.php");
exit;


