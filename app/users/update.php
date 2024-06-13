<?php
// Include your database connection file
include '../database/db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update query
    $sql = "UPDATE users SET 
            firstname = '$firstname',
            lastname = '$lastname',
            email = '$email',
            password = '$password',
            address = '$address',
            gender = '$gender',
            phone = '$phone'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        
        session_start();
        $_SESSION['edit_message'] = 'User updated successfully.';
    } else {
        $_SESSION['edit_message'] = 'Error updating employee: ' . mysqli_error($conn);
    } // Redirect back to index.php after update
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

// Close connection
mysqli_close($conn);
?>
