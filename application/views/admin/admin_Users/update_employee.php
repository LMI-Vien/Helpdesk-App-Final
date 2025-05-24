<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Form Update Employee
			<small>Update Details employee</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Update Details Employee</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Employee List</h3>
				<div class="box-tools pull-right">
            		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          		</div>
			</div>
			<form id="employeeUpdateForm" action="<?= site_url('AdminUsers_controller/employee_update'); ?>" method="POST">
				<input type="hidden" name="id" value="<?php echo $users_det['recid']; ?>">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Employee No.</label>
								<input type="text" name="emp_id" id="emp_id" value="<?php echo $users_det['emp_id']; ?>" class="form-control" readonly>
							</div>
							<div class="form-group">
								<label>Firstname</label>
								<input type="text" name="fname" id="fname" value="<?php echo htmlentities($users_det['fname']); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Middlename</label>
								<input type="text" name="mname" id="mname" value="<?php echo htmlentities($users_det['mname']); ?>" class="form-control">
							</div>
							<div class="form-group">
								<label>Lastname</label>
								<input type="text" name="lname" id="lname" value="<?php echo htmlentities($users_det['lname']); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Company Email</label>
								<input type="email" name="email" id="email" value="<?php echo $users_det['email']; ?>" class="form-control">
							</div>
							<div class="form-group">
								<label>Department</label>
								<select name="department" class="form-control">
							        <option value="">Please select department</option>
							        <?php foreach ($department_data[1] as $department): ?>
							        	<?php if ($users_det['dept_id'] == $department['recid']): ?>
								            <option value="<?php echo $department['recid']; ?>" selected><?php echo $department['dept_desc']; ?></option>
								        <?php else: ?>
								            <option value="<?php echo $department['recid']; ?>"><?php echo $department['dept_desc']; ?></option>
								        <?php endif; ?>
							        <?php endforeach; ?>
							    </select>
							</div>
							<div class="form-group">
								<label>Position</label>
								<input type="text" name="position" id="position" value="<?php echo htmlentities($users_det['position']); ?>" class="form-control">
							</div>
							<div class="form-group">
								<label>Role</label>
								<select name="role" id="role" class="form-control" required>
									<option value="" disabled <?php echo ($users_det['role'] == '') ? 'selected' : ''; ?>>Please choose the designated role</option>
									<option value="L1" <?php echo ($users_det['role'] == 'L1') ? 'selected' : ''; ?>>L1 (Regular Employees)</option>
									<option value="L2" <?php echo ($users_det['role'] == 'L2') ? 'selected' : ''; ?>>L2 (Supervisor/Admin)</option>
									<option value="L3" <?php echo ($users_det['role'] == 'L3') ? 'selected' : ''; ?>>L3 (Department Head)</option>
								</select>
							</div>
							<div class="form-group">
								<label>Username</label>
								<input type="text" name="username" id="username" value="<?php echo htmlentities($users_det['username']); ?>" class="form-control">
							</div>
							<div class="form-group">
								<label>Password</label> <span style="font-size: 12px;">(Leave blank if you don't need to update your password)</span>
								<input type="password" name="password" id="password" class="form-control">
							</div>
							<div class="form-group">
								<label>Confirm Password</label> <span style="font-size: 12px;">(Leave blank if you don't need to update your password)</span>
								<input type="password" name="cpassword" id="cpassword" class="form-control">
							</div>
							<div class="form-group">
								<label>
	                  				<input type="checkbox" class="minimal" id="showPassword">
	                  				Show Password
	                			</label>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button class="btn btn-info float-left">Submit</button>
						<a href="<?= base_url(); ?>sys/admin/users" class="btn btn-danger float-left">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>

<!-- Include jQuery -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

<style>
	/* Popup: Overall container */
	.swal-custom-popup {
		padding: 20px; /* Adjust padding */
	}

	/* Title: Customize font size and weight */
	.swal-custom-title {
		font-size: 3em; /* Larger title */
		font-weight: bold; /* Make the title bold */
	}

	/* Content: Customize font size and line spacing */
	.swal-custom-content {
		font-size: 5.5em; /* Larger content text */
		line-height: 1.6; /* Adjust line spacing */
	}

	/* Confirm Button: Customize size and padding */
	.swal-custom-confirm-btn {
		font-size: 1.6em; /* Larger button text */
		padding: 10px 20px; /* Adjust padding for the button */
	}

	/* Optional: Increase button border radius and background color */
	.swal-custom-confirm-btn, .swal-custom-cancel-btn {
		border-radius: 8px; /* Make the buttons more rounded */
		background-color: #007bff; /* Change button color */
		color: #fff; /* Ensure text is white */
	}

	.swal-custom-confirm-btn:hover, .swal-custom-cancel-btn:hover {
		background-color: #0056b3; /* Darker color on hover */
	}

	.swal-custom-text {
		font-size: 2em; /* Adjust the size as needed */
	}

	/* Optional: To ensure the custom styles are applied correctly */
	.swal2-html-container {
		font-size: 2em !important; /* Use !important if necessary to override defaults */
	}
</style>

<script>
$(document).ready(function() {
    $('#employeeUpdateForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        $.ajax({
            url: $(this).attr('action'), // Form action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Success alert
                    Swal.fire({
                        title: 'Successfully Updated!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        width: '30%',
                        heightAuto: false,
                        timer: 2000,
						customClass: {
                            popup: 'swal-custom-popup',
                            title: 'swal-custom-title',
							text: 'swal-custom-text',
                            content: 'swal-custom-content',
                            confirmButton: 'swal-custom-confirm-btn'
                        }
                    }).then(() => {
                        window.location.href = '<?= base_url("sys/admin/users"); ?>';
                    });
                } else {
                    // Error alert
                    Swal.fire({
                        title: 'Update Failed!',
                        text:  response.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        width: '30%',
                        heightAuto: false,
                        timer: 2000,
						customClass: {
							popup: 'swal-custom-popup',
							title: 'swal-custom-title',
							htmlContainer: 'swal-custom-text',
							content: 'swal-custom-content',
							confirmButton: 'swal-custom-confirm-btn'
						}
                    });
                }
            },
            error: function() {
                // Handle unexpected errors
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    width: '30%',
                    heightAuto: false
                });
            }
        });
    });
});
</script>