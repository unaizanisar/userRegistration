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
session_start();

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$id = $_GET['id'];
$sql = "SELECT * FROM blogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    die('Blog not found');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE blogs SET title = ?, content = ?, category_id = ?, user_id = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssiiii", $title, $content, $category_id, $user_id, $status, $id);
    
    if ($update_stmt->execute()) {
        $_SESSION['edit_message'] = "Blog updated successfully";
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

$sql_users = "SELECT * FROM users";
$result_users = mysqli_query($conn, $sql_users);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit Blog</title>
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
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Edit Blog</h1>
        <form method="post" action="edit.php?id=<?php echo $id; ?>" id="editForm">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" >
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" ><?php echo htmlspecialchars($blog['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" >
                    <?php while ($row = mysqli_fetch_assoc($result_categories)) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $blog['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="user_id">Author</label>
                <select class="form-control" id="user_id" name="user_id" >
                    <?php while ($row = mysqli_fetch_assoc($result_users)) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $blog['user_id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['firstname']); ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function(){
            
            $('#editForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    user_id:{
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "Title is required."
                    },
                    content: {
                        required: "Content is required."
                    },
                    category_id: {
                        required: "Category is required."
                    },
                    user_id: {
                        required: "User is required."
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                }
            });
        });
    
    </script>
</body>
</html>
</body>
</html>
