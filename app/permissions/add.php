<?php
include('../database/db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $module = $_POST['module'];
    
    $sql = "INSERT INTO permissions (name, module) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $name, $module);
        if($stmt->execute()){
            session_start();
            $_SESSION['create_message'] = 'New Permission Added Successfully.';
            header('Location:index.php');
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
