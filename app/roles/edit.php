<?php
include '../database/db.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php');
    exit();
}

// Fetch user details based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch role details
    $sql = "SELECT * FROM roles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $roles = $result->fetch_assoc();
    } else {
        $_SESSION['edit_message'] = 'Role not found.';
        header("Location: index.php");
        exit;
    }

    // Fetch permissions
    $permissionSql = "SELECT id, name FROM permissions ORDER BY module, name";
    $permissionResult = $conn->query($permissionSql);
    $permissions = [];
    if ($permissionResult->num_rows > 0) {
        while ($row = $permissionResult->fetch_assoc()) {
            $permissions[] = $row;
        }
    }

    // Fetch current role permissions
    $rolePermissionsSql = "SELECT permission_id FROM roles_has_permissions WHERE role_id = ?";
    $rolePermissionsStmt = $conn->prepare($rolePermissionsSql);
    $rolePermissionsStmt->bind_param("i", $id);
    $rolePermissionsStmt->execute();
    $rolePermissionsResult = $rolePermissionsStmt->get_result();
    $currentPermissions = [];
    while ($row = $rolePermissionsResult->fetch_assoc()) {
        $currentPermissions[] = $row['permission_id'];
    }
    $rolePermissionsStmt->close();
} else {
    $_SESSION['edit_message'] = 'No role ID provided.';
    header("Location: index.php");
    exit;
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit Roles</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .custom-control.custom-checkbox .custom-control-label {
        padding-left: 25px; /* Adjust this value as needed to create space between the checkbox and the label */
    }
    .custom-control-input {
        position: relative;
        margin-left: 0;
    }
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff; /* Bootstrap primary color */
        border-color: #007bff;
    }
</style>
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
                <a class="nav-link" href="./index.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Categories</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../blogs/index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Blogs</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./index.php">
                    <i class="fas fa-fw fa-briefcase"></i>
                    <span>Roles</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../permissions/index.php">
                    <i class="fas fa-fw fa-user-lock"></i>
                    <span>Permissions</span></a>
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
        <div class="container-fluid"> <br>
            <h1 class="h3 mb-2 text-gray-800">ROLES</h1>
            <div class="container mt-5">
                <h3>Fill out the form to edit role!</h3> <br>

                <form id="editForm" method="POST" action="update.php">
                    <input type="hidden" name="id" value="<?php echo $roles['id']; ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($roles['name']); ?>">
                    </div>
                    <div class="form-group">
                                <label>Permissions</label><br>
                                <?php foreach ($permissions as $permission) : ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="permission_<?php echo $permission['id']; ?>" name="permissions[]" value="<?php echo $permission['id']; ?>" <?php echo in_array($permission['id'], $currentPermissions) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="permission_<?php echo $permission['id']; ?>"><?php echo htmlspecialchars($permission['name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href = "index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script   script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
        $(document).ready(function(){
            $('#editForm').validate({
                rules: {
                    name: {
                        required: true
                    }
                },
                messages:{
                    name: {
                        required: "Name is required."
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element){
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass){
                    $(element).addClass('is-valid').removeClass('is-valid');
                },
                unhighlight: function(element, errorClass, validClass){
                    $(element).removeClass('is-valid').addClass('is-valid');
                }
            });
        });
    </script>
</body>
</html>