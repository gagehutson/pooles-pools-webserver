<?php
session_start();
require '../db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = $_POST['servicejob_id'] ?? null;
    $techId = $_POST['technician_id'] ?? null;
    $partNumber = $_POST['part_number'] ?? null;
    $checkoutDate = $_POST['checkout_date'] ?? null;
    $quantityUsed = $_POST['quantity_used'] ?? null;

    if ($jobId && $techId && $partNumber && $checkoutDate && $quantityUsed) {
        $updateStmt = $conn->prepare("UPDATE inventory SET total_quantity = total_quantity - ? WHERE part_number = ? AND total_quantity >= ?");
        $updateStmt->bind_param("iii", $quantityUsed, $partNumber, $quantityUsed);

        if ($updateStmt->execute() && $updateStmt->affected_rows > 0) {
            $insertStmt = $conn->prepare("INSERT INTO inventorycheckout (servicejob_id, technician_id, part_number, checkout_date, quantity_used) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->bind_param("iiisi", $jobId, $techId, $partNumber, $checkoutDate, $quantityUsed);

            if ($insertStmt->execute()) {
                $_SESSION['flash_message'] = "Checkout recorded and inventory updated.";
            } else {
                $_SESSION['flash_message'] = "Error logging checkout: " . $insertStmt->error;
            }

            $insertStmt->close();
        } else {
            $_SESSION['flash_message'] = "Not enough stock or error updating inventory.";
        }

        $updateStmt->close();
    } else {
        $_SESSION['flash_message'] = "Missing form data.";
    }
}

header("Location: products.php");
exit;


/*Developed by Karli Newberry*/