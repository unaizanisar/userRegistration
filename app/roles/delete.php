<?php
include '../database/db.php';

if (isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM roles WHERE id = $id";
    if(mysqli_query($conn, $sql)){
        session_start();
        $_SESSION['delete_message'] = "Role Deleted Successfully!";
    } else {
        echo "Error deleting role: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: index.php");
    exit();
} else{
    echo "No ID provided for deletion.";
}
?>
