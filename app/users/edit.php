<?php
include '../database/db.php';
include '../../includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['edit_message'] = 'User not found.';
    header("Location: index.php");
    exit();
}

$sql_roles = "SELECT * FROM roles WHERE name != 'Super Admin'";
$result_roles = mysqli_query($conn, $sql_roles);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit User</title>
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
                <a class="nav-link" href="./index.php">
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
                <a class="nav-link" href="../blogs/index.php">
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
        <!-- Sidebar items here... -->
    
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
                <h1 class="h3 mb-2 text-gray-800">Edit User</h1>
                <form id="editForm" action="update.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
                            <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="role_id">Role</label>
                        <select class="form-control" id="role_id" name="role_id">
                            <?php while ($role = mysqli_fetch_assoc($result_roles)): ?>
                            <option value="<?php echo htmlspecialchars($role['id']); ?>" <?php if ($user['role_id'] == $role['id']) echo 'selected'; ?>><?php echo htmlspecialchars($role['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">Profile Photo</label>
                        <input type="file" class="form-control-file" id="photo" name="photo">
                        <?php if ($user['photo']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Current Photo" style="max-height: 150px; margin-top: 10px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl8wJX6lqFz2Sc+hKjL2C/ScO7LO35ZNHJzjDq0BLF9" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9zt31j2+vKHz/4+nF9a6ibF5ttvT5E3sK29tVf78I4zP5S8bh67k" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-Gn5384xqQ1aoWXA+05836z1w0qU3vO3dk3r9F5ujy8j1x3sP16W0vW6D42haVtF9" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" crossorigin="anonymous"></script>
<script>
    <?php if (isset($_SESSION['edit_message'])): ?>
    $.toast({
        heading: 'Information',
        text: '<?php echo $_SESSION['edit_message']; ?>',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'info',
        hideAfter: 5000,
        stack: 6
    });
    <?php unset($_SESSION['edit_message']); endif; ?>
</script>
</body>
</html>
