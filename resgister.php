<?php
// Include database configuration file
include 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $office = mysqli_real_escape_string($conn, $_POST['office']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    // Attempt insert query execution
    $sql = "INSERT INTO users (name, position, office, age, startDate, salary) VALUES ('$name', '$position', '$office', '$age', '$startDate', '$salary')";
    if(mysqli_query($conn, $sql)){
        // Redirect to success page
        header("location: tables.html");
        exit();
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>
