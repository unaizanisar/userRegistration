<?php
include '../database/db.php';
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