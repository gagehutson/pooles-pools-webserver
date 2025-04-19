<?php
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['flash_message'] = "Item removed from cart.";
    }
}

header("Location: cart.php");
exit;
?>
