<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Check for update message in session
$updateMessage = isset($_SESSION['update_message']) ? $_SESSION['update_message'] : '';
unset($_SESSION['update_message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>User Profile</title>
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
                <a class="nav-link" href="./index.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../categories/index.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Categories</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../blogs/index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Blogs</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../roles/index.php">
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
        <img class="img-profile rounded-circle" src="<?php echo htmlspecialchars($_SESSION['profile_photo']) . '?v=' . time(); ?>">
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
        <h1 class="h3 mb-2 text-gray-800" style="margin:20px">Profile</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
            </div>
            <div class="card-body">
            <?php if (!empty($user['profile_photo'])): ?>
                <div class="text-center mb-4">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Profile Photo" class="img-thumbnail" style="max-height: 150px;">
                </div>
            <?php endif; ?>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstname']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastname']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <a href="profile_edit.php" class="btn btn-primary">Edit Details</a>
                
            </div>
        </div>
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
        $(document).ready(function() {
            <?php if (!empty($updateMessage)) { ?>
                $.toast({
                    heading: 'Update',
                    text: '<?php echo $updateMessage; ?>',
                    loader: false,
                    icon: 'success',
                    position: 'top-right',
                  
                });
            <?php } ?>
        });
    </script>
</body>
</html>
