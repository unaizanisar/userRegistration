<?php
include '../database/db.php';

if (isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM permissions WHERE id = $id";
    if(mysqli_query($conn, $sql)){
        session_start();
        $_SESSION['delete_message'] = "Permission Deleted Successfully!";
    } else {
        echo "Error deleting permission: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: index.php");
    exit();
} else{
    echo "No ID provided for deletion.";
}
?>
