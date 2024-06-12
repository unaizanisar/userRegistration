<?php
include 'db.php';

$id = $_POST['id'];

$sql = "DELETE FROM employees WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Employee deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
