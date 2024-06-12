<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $office = $_POST['office'];
    $age = $_POST['age'];
    $startDate = $_POST['startDate'];
    $salary = $_POST['salary'];

    $sql = "INSERT INTO employees (name, position, office, age, startDate, salary) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssisi", $name, $position, $office, $age, $startDate, $salary);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Employee added successfully"]);
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
