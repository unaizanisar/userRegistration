
<?php
include '../database/db.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Perform deletion query
    $sql = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Set a session variable to indicate successful deletion
        session_start();
        $_SESSION['delete_message'] = "Employee Deleted Successfully!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);

    // Redirect back to index.php
    header("Location: index.php");
    exit();
} else {
    echo "No user ID provided for deletion.";
}
?>