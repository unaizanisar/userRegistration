<?php
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // New field
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    // Check if email is unique
    $emailQuery = "SELECT * FROM users WHERE email=?";
    $emailStmt = $conn->prepare($emailQuery);
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();
    if ($emailResult->num_rows > 0) {
        $emailStmt->close();
        echo json_encode(["success" => false, "message" => "Email already exists."]);
        exit;
    }
    $emailStmt->close();

    // Check if phone number is unique
    $phoneQuery = "SELECT * FROM users WHERE phone=?";
    $phoneStmt = $conn->prepare($phoneQuery);
    $phoneStmt->bind_param("s", $phone);
    $phoneStmt->execute();
    $phoneResult = $phoneStmt->get_result();
    if ($phoneResult->num_rows > 0) {
        $phoneStmt->close();
        echo json_encode(["success" => false, "message" => "Phone number already exists."]);
        exit;
    }
    $phoneStmt->close();

    // Hash the password before storing it into DB
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (firstname, lastname, email, password, address, gender, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $hashed_password, $address, $gender, $phone);
        if ($stmt->execute()) {
            session_start();
            $_SESSION['create_message'] = 'New user added successfully.';
            header('Location: ../login.php');
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add user: " . $stmt->error]);
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
