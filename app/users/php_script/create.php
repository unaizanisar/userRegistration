<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}

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

    // Handle file upload
    $target_dir = "../../uploads/profile_photos/"; //sets the directory where uploaded file will be stored
    if (!is_dir($target_dir)) { //if directory doesn't exist, create it !
        mkdir($target_dir, 0755, true); //the true parameter allows the creation of nested directories if required
    }
    $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]); //concatenates the name of the directory with the name of the uploaded file
    $uploadOk = 1; //initialize upload status: to keep track that a file should be uploaded!
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profile_photo"]["size"] > 500000) { // 500KB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            // File uploaded successfully
            $profile_photo = $target_file;

            // Insert user data into the database
            $sql = "INSERT INTO users (firstname, lastname, email, password, address, gender, phone, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssssssis", $firstname, $lastname, $email, $hashed_password, $address, $gender, $phone, $profile_photo);
                if ($stmt->execute()) {
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
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>