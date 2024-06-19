<?php
// Include your database connection file
include '../database/db.php';
session_start(); // Start the session

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update query
    $sql = "UPDATE users SET 
            firstname = '$firstname',
            lastname = '$lastname',
            email = '$email',
            address = '$address',
            gender = '$gender',
            phone = '$phone'";
            
    if (!empty($password)) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_password = mysqli_real_escape_string($conn, $hashed_password);
        $sql .= ", password = '$hashed_password'";
    }

    $sql .= " WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['edit_message'] = 'User updated successfully.';
    } else {
        $_SESSION['edit_message'] = 'Error updating user: ' . mysqli_error($conn);
    }

    // Redirect back to index.php after update
    header("Location: index.php");
    exit();
}

// Close connection
mysqli_close($conn);
?>
