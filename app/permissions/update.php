<?php
include '../database/db.php';
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id=$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
   
    $sql = "UPDATE permissions SET
            name = '$name'";
    $sql .= "WHERE id = $id";
    if(mysqli_query($conn,$sql)){
        $_SESSION['edit_message'] = 'Permission updated successfully.';
    } else {
        $_SESSION['edit_message'] = 'Error updating permission.' . mysqli_error($conn);
    }
    header("Location:index.php");
    exit();
}
mysqli_close($conn);
?>