<?php
include '../database/db.php';

if (isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];

    // First, delete associated blogs
    $sql_blogs = "DELETE FROM blogs WHERE category_id = $id";
    if (!mysqli_query($conn, $sql_blogs)) {
        echo "Error deleting associated blogs: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    // Then, delete the category
    $sql_category = "DELETE FROM categories WHERE id = $id";
    if(mysqli_query($conn, $sql_category)){
        session_start();
        $_SESSION['delete_message'] = "Category Deleted Successfully!";
    } else {
        echo "Error deleting category: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: index.php");
    exit();
} else{
    echo "No ID provided for deletion.";
}
?>
