<?php
include '../database/db.php'; // Database connection
include '../../includes/config.php'; // Includes config and helper functions
session_start(); 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}

// Check if the user has permission to delete users
$permission_name = 'User Delete'; 
if (!hasRolePermission($_SESSION['role_id'], $permission_name)) {
    // If the user does not have permission, show an error message
    echo "You do not have permission to delete users.";
    exit();
}

// Proceed with deletion if user has permission
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // Sanitize the ID input

    // First, delete associated blogs
    $sql_blogs = "DELETE FROM blogs WHERE user_id = ?";
    $stmt = $conn->prepare($sql_blogs);
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "Error deleting associated blogs: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit();
    }

    // Then, delete the user
    $sql_user = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql_user);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['delete_message'] = "User Deleted Successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
} else {
    echo "No user ID provided for deletion.";
}
?>
