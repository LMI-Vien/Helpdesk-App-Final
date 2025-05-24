<div class="content-wrapper">
    <section class="content-header">
		<h1>
			Department Setup
			<small>Setting up Department</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Departments</li>
		</ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
                        <div class="table-responsive">
                            <table id="tblDepartment" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Department ID</th>
                                        <th>Department</th>
                                        <th>Manager ID</th>
                                        <th>Support ID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="display: flex; align-items: center; justify-content: center; height: 100vh; margin: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this department?
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

    .swal-custom-text {
        font-size: 2em; /* Adjust the size as needed */
    }

    /* Optional: To ensure the custom styles are applied correctly */
    .swal2-html-container {
        font-size: 2em !important; /* Use !important if necessary to override defaults */
    }
</style>

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recid = button.data('id');
        var deleteUrl = "<?= base_url('AdminDept_controller/department_delete/'); ?>" + recid;

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
                            title: "DEPARTMENT'S BEEN SUCCESSFULLY DELETED!",
							icon: "success",
							width: '30%', // Set the width if needed
							heightAuto: false, // Disable auto height
							timer: 2000,
                            customClass: {
                                popup: 'swal-custom-popup',
                                title: 'swal-custom-title',
                                text: 'swal-custom-text',
                                content: 'swal-custom-content',
                                confirmButton: 'swal-custom-confirm-btn'
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

