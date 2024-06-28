<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}?>
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
            header('Location: ../index.php');
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