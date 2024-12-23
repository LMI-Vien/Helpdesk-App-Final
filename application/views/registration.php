<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/login-design/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="<?= base_url(); ?>/assets/dist/dist/js/external/jquery/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script href="<?php echo site_url();?>/assets/toast/jqm.js"></script>
	<script href="<?php echo site_url();?>/assets/toast/toast.js"></script>

    <!-- SweetAlert CSS -->
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/sweetalert2.min.css">

    <!-- SweetAlert JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/js/sweetalert2.all.min.js">
    
</head>
<style>
    .yellow-shadow {
        box-shadow: 0 4px 10px rgba(236,159,35,255); /* Adjust spread and opacity as needed */
    }
</style>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card yellow-shadow">
                <div class="card-header text-center">
                    <h4 style ="font-size: 50px;" >Employee Registration</h4>
                </div>
                <div class="card-body">
                    <form id="registrationForm" action="<?= site_url('Main/registration'); ?>" method="POST">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="middlename" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename">
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <!--<select name="department" class="form-control" required>-->
                                    <select name="dept_name" id="dept_name" class="form-control" required>
                                        <option value="">Select Dept</option>
                                        <?php 
                                        if (!empty($get_departments)) {
                                            foreach($get_departments as $stuu) {
                                                echo '<option value="' . htmlspecialchars($stuu['recid']) . '">' . htmlspecialchars($stuu['dept_desc']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No departments available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" required>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="conpassword" name="conpassword" required>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="col-md-8 mx-auto">
                                    <button type="submit" class="btn btn-warning btn-block mt-3">Register</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="col-md-8 mx-auto">
                                <button class="btn btn-outline-warning btn-block mt-1" onclick="window.location.href='<?= base_url(); ?>'"><span style = "color: #000000">Back</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
     $('#registrationForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Form action URL
                data: $(this).serialize(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message with SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: response.message
                        }).then(function() {
                            // Optionally, you can redirect the user after the alert
                            window.location.href = '<?= base_url(); ?>sys/registration';
                        });
                    } else {
                        // Show error message with SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    // Handle error case
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            });
        });
</script>
</body>
</html>
