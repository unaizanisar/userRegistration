<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}?>
<?php
include '../database/db.php';

if (isset($_GET['id'])) {  
    $id = $_GET['id']; 
    $sql = "SELECT b.*, 
                   c.id as category_id, c.name as category_name, 
                   u.id as user_id, u.firstname as user_name 
            FROM blogs b
            LEFT JOIN categories c ON b.category_id = c.id
            LEFT JOIN users u ON b.user_id = u.id
            WHERE b.id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) { 
        $blog = mysqli_fetch_assoc($result);
    } else {
        echo "Blog not found.";
        exit;
    }
} else {
    echo "No blog ID provided.";
    exit;
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Blog Details</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard/index.php">
                <div class="sidebar-brand-text mx-3">
                    <img src="../../img/blogslogo.png" alt="Blogs Logo" style="max-height: 150px;">
                </div>
            </a>
            <li class="nav-item">
                <a class="nav-link" href="../dashboard/index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../users/index.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../categories/index.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Categories</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Blogs</span></a>
            </li>
        </ul>
        <div class="container mt-5">
            <h2 style="text-align:center">Blog Details</h2>
            <div class="mt-3">
                <p><strong>Title:</strong> <?php echo $blog['title']; ?></p> <br>
                <p><strong>Content:</strong> <?php echo $blog['content']; ?></p> <br>
                <p><strong>Category Name:</strong> <?php echo htmlspecialchars($blog['category_name']); ?></p> <br>
                <p><strong>User Name:</strong> <?php echo htmlspecialchars($blog['user_name']); ?></p> <br>
            </div> 
            <div style="text-align:center">
                <a href="index.php" class="btn btn-primary mt-3">Back to Blogs</a>
            </div>
        </div>
    </div>
</body>

</html>
