<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $_SESSION['show_login_modal'] = true;
    header("Location: cart.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total = 0;
$items_description = "";

foreach ($cart as $item) {
    if (!isset($item['part_number'])) {
        die("Error: part_number is required for all cart items.");
    }

    $line = $item['name'] . " x" . $item['qty'] . " ($" . $item['price'] . ")";
    $items_description .= $line . "; ";
    $total += $item['price'] * $item['qty'];
}

$order_date = date("Y-m-d H:i:s");
$date_due = date("Y-m-d", strtotime("+7 days"));
$status = "Unpaid";

foreach ($cart as $item) {
    $item_total = $item['price'] * $item['qty'];
    $part_number = $item['part_number'];

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, part_number, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiids", $customer_id, $part_number, $item['qty'], $item_total, $order_date);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO payment (customer_id, servicejob_id, order_id, amount, date_due, status) VALUES (?, NULL, ?, ?, ?, ?)");
    $stmt->bind_param("iidss", $customer_id, $order_id, $item_total, $date_due, $status);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE inventory SET total_quantity = total_quantity - ? WHERE part_number = ?");
    $stmt->bind_param("ii", $item['qty'], $part_number);
    $stmt->execute();
    $stmt->close();
}

$_SESSION['last_receipt'] = [
    'items' => $cart,
    'total' => $total,
    'date' => $order_date,
    'payment_method' => "Cash on Pickup",
    'due' => $date_due
];

unset($_SESSION['cart']);
header("Location: receipt.php");
exit;
?>



