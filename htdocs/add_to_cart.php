<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['product_id'];

  $stmt = $conn->prepare("SELECT part_number, item_name, cost_per_unit FROM inventory WHERE part_number = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $product = $result->fetch_assoc();

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id]['qty'] += 1;
    } else {
      $_SESSION['cart'][$id] = [
        'part_number' => $product['part_number'],
        'name' => $product['item_name'],
        'price' => $product['cost_per_unit'],
        'qty' => 1
      ];
    }

    $_SESSION['flash_message'] = "{$product['item_name']} added to cart!";
  }

  header("Location: shop.php");
  exit;
}
?>



