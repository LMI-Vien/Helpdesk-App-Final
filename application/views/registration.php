<?php $this->load->view('header'); ?>

<style>
    .yellow-shadow {
        box-shadow: 0 4px 10px rgba(236,159,35,255); /* Adjust spread and opacity as needed */
    }
</style>

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
                                    <select name="dept_name" id="dept_name" class="form-control">
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
                                    <button type="submit" class="btn btn-warning btn-block mt-3" id="submitBtn">Register</button>
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

<?php $this->load->view('footer'); ?>
<script>
    $(document).ready(function () {
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault(); 

            const username = $('#username').val().trim();
            const password = $('#password').val().trim();
            const deptName = $('#dept_name').val();

            // Client-side validation for username
            if (username.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Username',
                    text: 'Username must be at least 6 characters long.',
                });
                return; 
            }

            // Client-side validation for password
            if (password.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Password must be at least 6 characters long.',
                });
                return; 
            }

            if (!deptName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Department Required',
                    text: 'Please select a department.',
                });
                return; 
            }

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(), // Serialize form data
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: response.message,
                        }).then(function () {
                            window.location.href = '<?= base_url(); ?>sys/registration';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            });
        });

        $('#submitBtn').click(function () {
            $('#registrationForm').submit(); 
        });
});

</script>
