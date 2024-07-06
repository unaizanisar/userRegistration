<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';
include '../../includes/config.php';
session_start();

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete related entries in roles_has_permissions table
        $sql1 = "DELETE FROM roles_has_permissions WHERE permission_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $delete_id);
        $stmt1->execute();
        $stmt1->close();

        // Delete from permissions table
        $sql2 = "DELETE FROM permissions WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $delete_id);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['delete_message'] = "Permission deleted successfully!";
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction if any error occurs
        $conn->rollback();

        $_SESSION['delete_message'] = "Error deleting permission: " . $exception->getMessage();
    }
} else {
    $_SESSION['delete_message'] = "No permission ID specified for deletion.";
}

// Redirect back to the permissions page
header("Location: index.php");
exit();

?>
