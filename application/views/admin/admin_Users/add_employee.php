<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Form Add Employee
			<small>Add employee</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Add Employee</li>
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
			<form id="employeeAddForm" action="<?= site_url('AdminUsers_controller/employee_add'); ?>" method="POST">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Employee No.</label>
								<input type="text" name="emp_id" id="emp_id" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Firstname</label>
								<input type="text" name="fname" id="fname" class="form-control" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Middlename</label>
								<input type="text" name="mname" id="mname" class="form-control">
							</div>
							<div class="form-group">
								<label>Lastname</label>
								<input type="text" name="lname" id="lname" class="form-control" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Company Email</label>
								<input type="email" name="email" id="email" class="form-control">
							</div>
							<div class="form-group">
								<label>Department</label>
								<select name="department" class="form-control" required>
                                    <option value="" disabled selected>Please select department</option>
                                    <?php foreach ($department_data[1] as $department): ?>
                                        <option value="<?php echo $department['recid']; ?>"><?php echo $department['dept_desc']; ?></option>
                                    <?php endforeach; ?>
                                </select>
							</div>
							<div class="form-group">
								<label>Position</label>
								<input type="text" name="position" id="position" class="form-control">
							</div>
							<div class="form-group">
								<label>Role</label>
								<select name="role" id="role" class="form-control" required>
                                    <option value="" disabled selected>Please choose the designated role</option>
									<option value="L1">L1 (Regular Employees)</option>
									<option value="L2">L2 (Supervisor/Head)</option>	
									<option value="L3">L3 (SuperAdmin - ICT)</option>
                                </select>
							</div>
							<div class="form-group">
								<label>Username</label>
								<input type="text" name="username" id="username" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" id="password" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Confirm Password</label>
								<input type="password" name="cpassword" id="cpassword" class="form-control" required>
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
    $('#employeeAddForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        $.ajax({
            url: $(this).attr('action'), // Form action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                console.log(response); // Debugging: Check the response in the console
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
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
                        // Redirect or perform other actions after success
                        window.location.href = '<?= base_url("sys/admin/users"); ?>';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
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
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error); // Debugging: Log the error to the console
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
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
                });
            }
        });
    });
});

</script>