<?php
session_start();
require 'db.php';

$customer_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $customer_id) {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $stmt = $conn->prepare("UPDATE customer SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE customer_id = ?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $phone, $customer_id);
    $stmt->execute();
    $stmt->close();

    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        if ($newPassword !== $confirmPassword) {
            header("Location: account.php?error=nomatch");
            exit;
        }

        $stmt = $conn->prepare("SELECT password_hash FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            header("Location: account.php?error=wrongpass");
            exit;
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE customer SET password_hash = ? WHERE customer_id = ?");
        $stmt->bind_param("si", $newHash, $customer_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: account.php?updated=true");
    exit;
}
?>


<!--Developed by Jackson-->

