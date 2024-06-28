<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    // Update user details in the database
    $query = "UPDATE users SET firstname=?, lastname=?, email=?, address=?, gender=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $address, $gender, $phone, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['update_message'] = "Profile updated successfully.";
    } else {
        $_SESSION['update_message'] = "No changes made.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to profile.php with the message
    header('Location: profile.php');
    exit();
}
