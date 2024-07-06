<?php
include '../database/db.php';
include '../../includes/config.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}?>
<?php
include '../database/db.php';
$categoryQuery = "SELECT id, name FROM categories";
$categories = $conn->query($categoryQuery);
$userQuery = "SELECT id, firstname FROM users";
$users = $conn->query($userQuery);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("INSERT INTO blogs (title, content, category_id, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii",$title, $content, $category_id, $user_id);
    if($stmt-> execute()){
        session_start();
        $_SESSION['create_message'] = 'New Blog Added Successfully.';
        header("Location: index.php");
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add user: " . $stmt->error]);
    }
    $stmt->close();

    $conn->close();
}
?>

