<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Employee
			<small>List of employee</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">List of Employee</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<table id="tblUsers" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Username</th>
									<th>Role</th>
                                    <th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Modal Show Lock Account -->
<div class="modal fade" id="modalLock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Helpdesk Notification</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<form action="<?= site_url('Main/lock_users');?>" method="POST">
				<input type="hidden" name="id" id="id">
				<div class="modal-body">
					<p>Do you want to Lock this Employee ID: <span id="employee_id"></span></p>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-success float-right">Submit</button>
			    </div>
			</form>
		</div>
	</div>
</div>
<!-- Modal Show Unlock Account -->
<div class="modal fade" id="modalUnlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Helpdesk Notification</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<form action="<?= site_url('Main/unlock_users');?>" method="POST">
				<input type="hidden" name="id" id="recid">
				<div class="modal-body">
					<p>Do you want to Unlock this Employee ID: <span id="employee_id"></span></p>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-success float-right">Submit</button>
			    </div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="UsersDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document" style="display: flex; align-items: center; justify-content: center; height: 100vh; margin: auto;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this Employee?
			</div>
			<div class="modal-footer">      
				<a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>



<style>
	/* Popup: Overall container */
	.swal-custom-popup {
		padding: 20px; /* Adjust padding */
	}

	/* Title: Customize font size and weight */
	.swal-custom-title {
		font-size: 2em; /* Larger title */
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
</style>

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#UsersDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recid = button.data('id');
<<<<<<< HEAD
        var deleteUrl = "<?= base_url('AdminUsersController/employee_delete/'); ?>" + recid;
=======
        var deleteUrl = "<?= base_url('AdminUsers_controller/employee_delete/'); ?>" + recid;
>>>>>>> 9f9f7935d5608a2f4cea6e42711a51044c142247

        var confirmBtn = $(this).find('#confirmDeleteBtn');
        confirmBtn.off('click').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: deleteUrl,
                type: 'POST',
                success: function(response) {
                    response = JSON.parse(response); // Parse the JSON response

                    if (response.status === 'success') {
                        Swal.fire({
                            title: "EMPLOYEE'S BEEN SUCCESSFULLY DELETED!",
							icon: "success",
							width: '30%', // Set the width if needed
							heightAuto: false, // Disable auto height
							timer: 2000,
							customClass: {
								popup: 'swal-custom-popup', // Custom class for the popup
								title: 'swal-custom-title',  // Custom class for the title
								content: 'swal-custom-content', // Custom class for the content	
								confirmButton: 'swal-custom-confirm-btn' // Custom class for the confirm button
							}
                        }).then(() => {
                            window.location.reload(); // Reload the page or redirect as needed
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "An unexpected error occurred.",
                        icon: "error"
                    });
                }
            });
        });
    });
});

</script>