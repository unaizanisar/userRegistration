<?php
include '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO users (firstname, lastname, email, password, address, gender, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $password, $address, $gender, $phone);
        if ($stmt->execute()) {
            session_start();
            $_SESSION['create_message'] = 'New user added successfully.';
            header('Location: ../index.php');
            exit;
            // echo json_encode(["success" => true, "message" => "Employee added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add employee: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare statement: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>