<?php
include '../database/db.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // First, delete associated blogs
    $sql_blogs = "DELETE FROM blogs WHERE user_id = $id";
    if (!mysqli_query($conn, $sql_blogs)) {
        echo "Error deleting associated blogs: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    // Then, delete the user
    $sql_user = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $sql_user)) {
        session_start();
        $_SESSION['delete_message'] = "User Deleted Successfully!";
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: index.php");
    exit();
} else {
    echo "No user ID provided for deletion.";
}
?>
