<?php
// user_session.php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
$_SESSION['profile_photo'] = $user['profile_photo']; // Store the profile photo URL

$stmt->close();
$conn->close();
?>
