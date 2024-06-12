<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$position = $_POST['position'];
$office = $_POST['office'];
$age = $_POST['age'];
$startDate = $_POST['startDate'];
$salary = $_POST['salary'];

$sql = "UPDATE employees SET name='$name', position='$position', office='$office', age=$age, startDate='$startDate', salary=$salary WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Employee updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
