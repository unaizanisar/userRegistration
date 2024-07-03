<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    // Update user details in the database
    $query = "UPDATE users SET firstname=?, lastname=?, email=?, address=?, gender=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssii", $firstname, $lastname, $email, $address, $gender, $phone, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['update_message'] = "Profile updated successfully.";
    } else {
        $_SESSION['update_message'] = "No changes made.";
    }
    $_SESSION['user_name'] = $firstname . ' ' . $lastname;
    // Handle profile photo upload
    $target_dir = "../uploads/profile_photos/"; //directory
    if (!is_dir($target_dir)) { //create directory if it is not present
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

                // Update profile photo in the database
                $photo_query = "UPDATE users SET profile_photo=? WHERE id=?";
                $photo_stmt = $conn->prepare($photo_query);
                $photo_stmt->bind_param("si", $profile_photo, $userId);
                $photo_stmt->execute();
                $photo_stmt->close();

                // Update session variable
                $_SESSION['profile_photo'] = $profile_photo;

                $_SESSION['update_message'] = "Profile photo updated successfully.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect back to profile.php with the message
    header('Location: profile.php');
    exit();
}
?>
