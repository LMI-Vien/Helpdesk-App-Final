<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Concern Creation
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">TRACC Concern Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Ticket for TRACC Concern</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form id="TRC_form" action="<?= site_url('UsersTraccCon_controller/user_creation_tickets_tracc_concern'); ?>" method="POST" enctype="multipart/form-data">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>Control Number</label>
                                                    <input type="text" name="control_number" id="control_number" class="form-control" value="" required pattern=".*-.*" title="Control number must contain a hyphen (-)" oninput="this.value = this.value.toUpperCase().trim().replace(/\s+/g, '');">
			                    				</div>                                               
			                    			</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Module Affected</label>
                                                    <input type="text" name="module_affected" id="module_affected" class="form-control" value="" required>
			                    				</div>                                                
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Company</label>
                                                    <select class="form-control select2" name="company" id="company"  value="" required> 
                                                        <option value=""disabled selected>Company Category</option>
                                                        <option value="LMI">LMI</option>
                                                        <option value="RGDI">RGDI</option>
                                                        <option value="LPI">LPI</option>
                                                        <option value="SV">SV</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                        <textarea class="form-control" name="details_concern" id="details_concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <input type="file" name="uploaded_photo" id="uploaded_photo" class="form-control" accept="image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Reported by</label>
                                                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6" style = "display: none;">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                        <input type="text" name="department_description" id="department_description" value="<?php echo isset($get_department[1]['dept_desc']) ? htmlspecialchars($get_department[1]['dept_desc']) : ''; ?>" class="form-control select2" style="width: 100%;" readonly>
                                                        <input type="hidden" name="dept_id" value="<?php echo $users_det['dept_id']; ?>">
                                                        <!-- If supervisor_id is also needed -->
                                                        <input type="hidden" name="sup_id" value="<?php echo htmlspecialchars($users_det['sup_id']); ?>">
                                                </div>    
                                            </div>   

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Reported</label>
                                                    <input type="date" name="date_rep" id="date_rep" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="submitBtn" type="submit" class="btn btn-primary">Submit Tickets</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .swal-wide {
        width: 400px !important;
        font-size: 1.4rem; 
    }
</style>

<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Set the current date in YYYY-MM-DD format
        $('#date_rep').val(new Date().toISOString().split('T')[0]);

        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        $('#details_concern').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#details_concern').each(autoResizeTextarea);
    

    $('#submitBtn').click(function (e) {
            e.preventDefault();

            var form = document.getElementById('TRC_form');
            if (!form.checkValidity()) {
                form.reportValidity(); 
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!',
                customClass: {
                    popup: 'swal-wide' 
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#TRC_form').submit(); 
                }
            });
        });
    });

</script>