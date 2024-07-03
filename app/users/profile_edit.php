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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Profile Edit</title>
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
                                <img class="img-profile rounded-circle" src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
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
    <div class="container">
        <h1 class="h3 mb-2 text-gray-800">Edit Profile</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit User Details</h6>
            </div>
            <div class="card-body">
                <form id="editForm" method="POST" action="profile_update.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" >
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" >
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" >
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" >
                    </div>
                    <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" >
                                    <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                    <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                </select>
                            </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" >
                    </div>
                    <div class="form-group">
                                <label for="profile_photo">Profile Photo</label>
                                <?php if (!empty($user['profile_photo'])): ?>
                                    <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Profile Photo" style="max-height: 100px;"><br>
                                <?php endif; ?>
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                            </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
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
        $(document).ready(function() {
            $('#editForm').validate({
                rules: {
                    firstname: {
                        required: true
                    },
                    lastname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    
                    address: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 11,
                        digits: true, // Ensures that only digits are entered
                        maxlength: 11 // Ensures the phone number is exactly 11 digits
                    }
                },
                messages: {
                    firstname: {
                        required: "First name is required."
                    },
                    lastname: {
                        required: "Last name is required."
                    },
                    email: {
                        required: "Email address is required.",
                        email: "Please enter a valid email address."
                    },
                    
                    address: {
                        required: "Address is required."
                    },
                    gender: {
                        required: "Gender is required."
                    },
                    phone: {
                        required: "Phone number is required.",
                        digits: "Please enter only digits",
                        minlength: "Phone number must be exactly 11 digits",
                        maxlength: "Phone number must be exactly 11 digits"
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
            $('#phone').on('input', function() {
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }
            });
        });
    </script>
</body>
</html>
