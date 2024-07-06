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
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Blog</title>
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
            <?php
            $permission_name = 'User Listing'; 
            if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
            <li class="nav-item">
                <a class="nav-link" href="../users/index.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <?php }?>
            <?php
            $permission_name = 'Category Listing'; 
            if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
            <li class="nav-item">
                <a class="nav-link" href="../categories/index.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Categories</span></a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Blogs</span></a>
            </li>
            <?php
            $permission_name = 'Roles Listing'; 
            if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
            <li class="nav-item">
                <a class="nav-link" href="../roles/index.php">
                    <i class="fas fa-fw fa-briefcase"></i>
                    <span>Roles</span></a>
            </li>
            <?php }?>
            <?php
            $permission_name = 'Permissions Listing'; 
            if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
            <li class="nav-item">
                <a class="nav-link" href="../permissions/index.php">
                    <i class="fas fa-fw fa-user-lock"></i>
                    <span>Permissions</span></a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a class="nav-link" href="../authentication/logout.php">
                    <i class="fas fa-fw fa-sign-out"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <img class="img-profile rounded-circle" src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>">
                    </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../users/profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="../authentication/logout.php">
                                <i class="fas fa-sm fa-right-from-bracket mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
        <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Add new Blog</h1>
        <div class="card shadow mb-4">
        <div class="card-body">
        <div class="table-responsive">
            <form id="registerForm" method="POST" action="add.php"> 
                <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" >
                </div>
                <div class="form-group">
                <label for="content">Content</label>
                <input type="text" class="form-control" id="content" name="content" >
                </div>
                <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" class="form-control">
                   <option value="">Select a category</option>
                   <?php if ($categories->num_rows > 0): ?>
                         <?php while ($category = $categories->fetch_assoc()): ?>
                         <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                    <option value="">No categories available</option>
                    <?php endif; ?>
                </select>
                </div>
                <div class="form-group">
                <label for="user_id">User:</label>
                <select id="user_id" name="user_id" class="form-control">
                    <option value="">Select a user</option>
                        <?php if ($users->num_rows > 0): ?>
                            <?php while ($user = $users->fetch_assoc()): ?>
                            <option value="<?= $user['id'] ?>"><?= $user['firstname'] ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                        <option value="">No users available</option>
                        <?php endif; ?>
                </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Blog</button>
            </form>
        </div>
        </div>
        </div>
        </div>
        <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website <span id="year"></span></span>
                    </div>
                </div>
            </footer>

    </div>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
        $(document).ready(function(){
            
            $('#registerForm').validate({
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