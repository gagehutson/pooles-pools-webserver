<?php
session_start();
require 'db.php';

$username = $_POST['uname'] ?? '';
$password = $_POST['psw'] ?? '';

if (!$username || !$password) {
    echo "Please enter username and password.";
    exit;
}

$stmt1 = $conn->prepare("SELECT customer_id, firstname, password_hash, role FROM customer WHERE username = ?");
$stmt1->bind_param("s", $username);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows === 1) {
    $user = $result1->fetch_assoc();
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    }
}
$stmt1->close();
$stmt2 = $conn->prepare("SELECT technician_id, firstname, password_hash, role FROM technician WHERE username = ?");
$stmt2->bind_param("s", $username);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows === 1) {
    $tech = $result2->fetch_assoc();
    if (password_verify($password, $tech['password_hash'])) {
        $_SESSION['user_id'] = $tech['technician_id'];
        $_SESSION['firstname'] = $tech['firstname'];
        $_SESSION['role'] = $tech['role'];
        header("Location: /ADMIN/adminindex.php");
        exit;
    }
}
$stmt2->close();


echo "Invalid username or password.";
$conn->close();
?>

<!--Developed by Gage Hutson-->