<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}?>

<?php
include '../database/db.php';
session_start();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $newStatus = $_GET['status'] == 1 ? 0 : 1; // Toggle status

    $sql = "UPDATE users SET status = $newStatus WHERE id = $userId";
    if (mysqli_query($conn, $sql)) {
        if ($newStatus == 1) {
            $_SESSION['edit_message'] = 'User activated successfully.';
        } else {
            $_SESSION['edit_message'] = 'User deactivated successfully.';
        }
    } else {
        $_SESSION['edit_message'] = 'Error updating user status.';
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
