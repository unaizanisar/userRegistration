<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}

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

    // Start building the SQL query
    $sql = "UPDATE users SET 
            firstname = '$firstname',
            lastname = '$lastname',
            email = '$email',
            address = '$address',
            gender = '$gender',
            phone = '$phone'";
    $_SESSION['user_name'] = $firstname . ' ' . $lastname;
    // Handle file upload
    $target_dir = "../uploads/profile_photos/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    if (isset($_FILES["profile_photo"]) && $_FILES["profile_photo"]["error"] == UPLOAD_ERR_OK) {
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION));

        // Generate a unique file name
        $uniqueFileName = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $uniqueFileName;

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
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
                $sql .= ", profile_photo = '$profile_photo'";

                // Update session variable
                $_SESSION['profile_photo'] = $profile_photo;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

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
