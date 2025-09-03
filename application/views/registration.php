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
                                    <label for="employee_id" class="form-label">Employee ID &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="text" class="form-control letters-only" id="firstname" name="firstname" required>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="middlename" class="form-label">Middle Name &nbsp;<span style="font-size: smaller; color: red;">(Optional)</span></label>
                                    <input type="text" class="form-control letters-only" id="middlename" name="middlename">
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="text" class="form-control letters-only" id="lastname" name="lastname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <!--<select name="department" class="form-control" required>-->
                                    <select name="dept_name" id="dept_name" class="form-control" required>
                                        <option value="">Select Dept</option>
                                        <?php 
                                        if (!empty($get_departments)) {
                                            foreach($get_departments as $stuu) {
                                                if($stuu['dept_desc'] == 'Information Communication Technology') {
                                                    continue;
                                                }
                                                echo '<option value="' . htmlspecialchars($stuu['recid']) . '">' . htmlspecialchars($stuu['dept_desc']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No departments available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="position" class="form-label">Position &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="text" class="form-control letters-only" id="position" name="position" required>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" minlength="6" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">Confirm Password &nbsp;<span style="font-size: smaller; color: red;">*</span></label>
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
        $('#registrationForm').on('submit', function(e) {
            e.preventDefault(); 

            const password = $('#password').val();
            const conpassword = $('#conpassword').val();

            if (password !== conpassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords Do Not Match',
                    text: 'Please ensure both password fields are identical.'
                });
                return; // Stop the form submission
            }

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), 
                data: $(this).serialize(), 
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message with SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            timer: 2000,
                            text: response.message
                        }).then(function() {
                            window.location.href = '<?= base_url(); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            });
        });

        $('.letters-only').on('keydown', function (e) {
            // Prevent numbers
            if (e.key >= '0' && e.key <= '9') {
                e.preventDefault();
            }

            // Prevent special characters
            const regex = /^[A-Za-z\s]+$/;
            if (!regex.test(e.key) && !e.ctrlKey && !e.metaKey && !e.altKey) {
                e.preventDefault();
            }
        });

        $('#employee_id').on('keydown', function (e) {
        if (
            !(e.key >= '0' && e.key <= '9') && 
            e.key !== '-' &&          
            e.key !== 'Backspace' &&     
            e.key !== 'Shift' &&       
            e.key !== '*'
        ) {
            e.preventDefault();
        }
    });

    });
</script>
