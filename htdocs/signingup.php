<?php
session_start();
require 'db.php';

$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$firstname || !$lastname || !$email || !$phone || !$username || !$password || !$confirm_password) {
    echo "Please fill in all required fields.";
    exit;
}

if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO customer (firstname, lastname, email, phone, username, password, role) VALUES (?, ?, ?, ?, ?, ?, 'customer')");
$stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $username, $passwordHash);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['role'] = 'customer';


    header("Location: index.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>


<!--Developed by Gage Hutson-->