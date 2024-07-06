<?php
include '../database/db.php';
include '../../includes/config.php';
session_start();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $newStatus = $_GET['status'] == 1 ? 0 : 1; // Toggle status

    $sql = "UPDATE permissions SET status = $newStatus WHERE id = $userId";
    if (mysqli_query($conn, $sql)) {
        if ($newStatus == 1) {
            $_SESSION['edit_message'] = 'Permissions activated successfully.';
        } else {
            $_SESSION['edit_message'] = 'Permissions deactivated successfully.';
        }
    } else {
        $_SESSION['edit_message'] = 'Error updating permission status.';
    }

    header("Location: index.php");
    exit();
} else {
    $_SESSION['edit_message'] = 'Invalid user ID.';
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>
