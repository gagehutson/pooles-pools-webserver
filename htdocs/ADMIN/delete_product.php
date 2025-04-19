<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    die("Unauthorized");
}

require '../db.php';

if (isset($_GET['part_number'])) {
    $part_number = intval($_GET['part_number']);

    $stmt = $conn->prepare("DELETE FROM inventory WHERE part_number = ?");
    $stmt->bind_param("i", $part_number);
    if ($stmt->execute()) {
        header("Location: products.php?deleted=1");
        exit;
    } else {
        echo "Error deleting item. Try again.";
    }
} else {
    echo "Missing part number. Not an actual item.";
}
?>

<!--Developed by Jackson-->