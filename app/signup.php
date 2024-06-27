<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sign Up</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Sign up!</h1>
                                    </div>
                <form class="user" id="registerForm" method="POST" action="./authentication/signup.php">
                    <div class="form-group">
                        <!-- <label for="name">First Name</label> -->
                        <input type="text" id="firstname" name="firstname" placeholder="First Name" class="form-control form-control-user">
                    </div>
                    <div class="form-group">
                        <!-- <label for="lastname">Last Name</label> -->
                        <input type="text" placeholder="Last Name" class="form-control form-control-user" id="lastname" name="lastname" >
                    </div>
                    <div class="form-group">
                        <!-- <label for="email">Email</label> -->
                        <input type="email" placeholder="Email" class="form-control form-control-user"" id="email" name="email" >
                    </div>
                    <div class="form-group">
                        <!-- <label for="password">Password</label> -->
                        <input type="password" placeholder="Password" class="form-control form-control-user" id="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <!-- <label for="confirm_password">Confirm Password</label> -->
                        <input type="password" placeholder="Confirm Password" class="form-control form-control-user" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <!-- <label for="address">Address</label> -->
                        <input type="text" placeholder="Address" class="form-control form-control-user" id="address" name="address" >
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div>
                            <input type="radio" id="male" name="gender" value="Male" required>
                            <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="Female">
                            <label for="female">Female</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" placeholder="Phone" class="form-control form-control-user" id="phone" name="phone" required pattern="\d{11}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Sign Up</button>
                    <div>
                        <br>
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').validate({
                rules: {
                    firstname: "required",
                    lastname: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                    },
                    address: "required",
                    gender: "required",
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 11,
                        maxlength: 11
                    }
                },
                messages: {
                    firstname: "Please enter your first name",
                    lastname: "Please enter your last name",
                    email: "Please enter a valid email address",
                    password: "Please enter a password with at least 6 characters",
                    confirm_password: {
                    required: "Please enter confirm password",
                    minlength: "Confirm password must be at least 6 characters",
                    equalTo: "Passwords do not match"
                },
                    address: "Please enter your address",
                    gender: "Please select your gender",
                    phone: "Please enter a valid 11-digit phone number"
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
