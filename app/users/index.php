<?php

session_start();
include '../database/db.php';
include '../../includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user details if not already set in session
if (!isset($_SESSION['user_name']) || !isset($_SESSION['profile_photo']) || !isset($_SESSION['user_role'])) {
    $userId = $_SESSION['user_id'];

    // Modify SQL query to include role names
    $query = "SELECT users.*, roles.name AS role_name 
              FROM users 
              LEFT JOIN roles ON users.role_id = roles.id 
              WHERE users.id=?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Store user details in session
    $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
    $_SESSION['profile_photo'] = $user['profile_photo']; // Store the profile photo URL
    $_SESSION['user_role'] = $user['role_name']; // Store the user role name

    $stmt->close();
}

// Check if the user has permission to view users

// Check for messages
$deleteMessage = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';
$createMessage = isset($_SESSION['create_message']) ? $_SESSION['create_message'] : '';
$editMessage = isset($_SESSION['edit_message']) ? $_SESSION['edit_message'] : '';

// Clear the session variables after displaying the messages
unset($_SESSION['delete_message']);
unset($_SESSION['create_message']);
unset($_SESSION['edit_message']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Users Table</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
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
        <a class="dropdown-item" href="profile.php">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
        </a>
        <a class="dropdown-item" href="../authentication/logout.php">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
        </a>
    </div>
</li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">BLOGS</h1>
                    <div class="col text-right">
                        <a href="./create.php" class="btn btn-primary">Add New User</a>
                    </div> <br>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Users Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="users_table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Gender</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                        $sql = "SELECT users.*, roles.name AS role_name FROM users LEFT JOIN roles ON users.role_id = roles.id ORDER BY users.id DESC";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $count = 0;
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?php echo ++$count; ?></td>
                                                    <td><?php echo $row["firstname"]; ?></td>
                                                    <td><?php echo $row["lastname"]; ?></td>
                                                    <td><?php echo $row["email"]; ?></td>
                                                    <td><?php echo $row["address"]; ?></td>
                                                    <td><?php echo $row["gender"]; ?></td>
                                                    <td><?php echo $row["phone"]; ?></td>
                                                    <td><?php echo $row["role_name"];?></td>
                                                    <td><?php
                                                        if ($row["status"] == 1) {
                                                            echo '<span class="badge badge-success">Active</span>';
                                                        } else {
                                                            echo '<span class="badge badge-danger">In-active</span>';
                                                        }
                                                        ?></td>
                                                <!-- Actions buttons -->
                                                        <?php
                                                        $permission_name = 'User Detail'; 
                                                        if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
                                                        <td>
                                    
                                                        <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="Details"><i class='fa fa-eye'></i></a> |
                                                        <?php }?>
                                                        <?php
                                                        $permission_name = 'User Edit'; 
                                                        if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
                                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit"><i class='fa fa-pen'></i></a> |
                                                        <?php }?>
                                                        <?php
                                                        $permission_name = 'User Delete'; 
                                                        if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
                                                            
                                                        <a href="delete.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');"><i class='fa fa-trash'></i></a> |
                                                        <?php }?>
                                                        <?php
                                                        $permission_name = 'User Change Status'; 
                                                        if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
                                                        <?php
                                                        if ($row["status"] == 1) {
                                                            echo "<a href='status.php?id=" . $row['id'] . "&status=" . $row['status'] . "' class='btn btn-sm btn-danger' title='INACTIVE' onclick='return confirm(\"Are you sure you want to in-active this user?\");'><i class='fa fa-user-xmark'></i></a>";
                                                        } else {
                                                            echo "<a href='status.php?id=" . $row['id'] . "&status=" . $row['status'] . "' class='btn btn-sm btn-success' title='ACTIVE' onclick='return confirm(\"Are you sure you want to active this user?\");'><i class='fa fa-user-check'></i></a>";
                                                        }
                                                        ?><?php }?>
                                                    </td>
                                                </tr>

                                        <?php
                                            }
                                        } else {
                                            echo "<tr style='text-align: center'><td colspan=9>Record Not Found</td></t>";
                                        }

                                        // Close connection
                                        mysqli_close($conn);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
    </div>
   
    </div>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
  
    <script src="../../https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
 
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
        $(document).ready(function() {
            $('#users_table').DataTable();
            // Function to display toast based on delete message
            <?php if (!empty($deleteMessage)) : ?>
                $.toast({
                    heading: 'Deleted',
                    text: '<?php echo $deleteMessage; ?>',
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                    loaderBg: '#9EC600'
                });
            <?php endif; ?>

            // Function to display toast based on updated message
            <?php if (!empty($editMessage)) : ?>
                $.toast({
                    heading: 'Updated!',
                    text: '<?php echo $editMessage; ?>',
                    icon: 'success',
                    position: 'top-right',
                    loader: false,
                    loaderBg: '#9EC600'
                });
            <?php endif; ?>

            // Function to display toast based on createmessage
            <?php if (!empty($createMessage)) : ?>
                $.toast({
                    heading: 'Added!',
                    text: '<?php echo $createMessage; ?>',
                    icon: 'success',
                    position: 'top-right',
                    loader: false,
                    loaderBg: '#9EC600'
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>
<!--php -S localhost:3636 -t .-->
<!-- id 
category 
title,
text(body),
imgs,
createdat, 
category crud id name, status..,  -->

