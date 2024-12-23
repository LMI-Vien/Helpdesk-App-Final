<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ICT Helpdesk Application</title>

    <link rel="icon" href="<?= base_url(); ?>assets/images/lifestrong-logo.png" type="image/png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/login-design/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert CSS -->
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/sweetalert2.min.css">

    <!-- SweetAlert JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/js/sweetalert2.all.min.js">

    <style>
        .swal2-container {
            position: absolute !important;  /* Make sure it's fixed */
            z-index: 10000 !important;   /* Ensure it's on top */
        }

        body.swal2-shown {
            overflow: hidden !important; /* Prevent page scrolling when modal is shown */
        }

        .login-page {
            height: 100vh;               /* Full viewport height */
            display: flex;
            align-items: center;         /* Center vertically */
            justify-content: center;     /* Center horizontally */
        }

        .login-box {
            position: fixed;
            top: 120px;
            z-index: 1;                  /* Ensure it stays above background */
        }

        #username:focus {
            outline: none; 
            border-color: #ffc107; 
            box-shadow: 0 0 5px #ffc107; 
        }

        #password:focus {
            outline: none; 
            border-color: #ffc107; 
            box-shadow: 0 0 5px #ffc107; 
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header text-center">
                <b class="h1">ICT Helpdesk</b>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form id="loginForm" class="debug" action="<?= site_url('Main/login'); ?>" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user" style="color: #9a1a1f;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock" style="color: #9a1a1f;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-warning btn-block"><span style="color: #000000">Sign In</span></button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <a href="sys/registration" class="btn btn-outline-warning btn-block mt-1"><span style="color: #000000">Register</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>assets/dist/login-design/js/adminlte.min.js"></script>

    <!-- AJAX form submission with SweetAlert2 -->
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Form action URL
                data: $(this).serialize(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Redirect on success
                        window.location.href = response.redirect_url;
                    } else if(response.status === 'error') {
                        let errorMessage = response.message;
                        if (response.errors) {
                            for (let key in response.errors) {
                                errorMessage += `\n${response.errors[key]}`;
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: response.message
                        });
                    } else {
                        // Show validation errors if any
                        let errorMessage = response.message;
                        if (response.errors) {
                            for (let key in response.errors) {
                                errorMessage += `\n${response.errors[key]}`;
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: 'Username and Password is required.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Username or password is incorrect'
                    });
                }
            });
        });
    </script>
</body>
</html>
