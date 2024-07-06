<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../database/db.php';
include '../../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
       
        if (password_verify($password, $user['password'])) {
            if($user['status'] == 0){
               
                header('Location: admin_support.php');
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['firstname']. ' ' . $user['lastname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role_id'];
                // Set role permissions in the session
                // hasRolePermission(1,2);
                setRoleSession($user['role_id']);

                header('Location: ../dashboard/index.php'); 
                exit;
            }
           
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Email not found";
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}

$conn->close();
?>

<!-- helper function use throughout app, ksi b page me hon access krlein
roles session
permissions session
kis user ko ye role -->
