<!--  DUMMY CODE -->
<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				MSRF Details
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">MSRF Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Ticket for MSRF</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/update_status_msrf_assign'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>MSRF#</label>
			                    					<input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?= $msrf['ticket_id']; ?>" readonly>
			                    				</div>
			                    			</div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Requestor</label>
			                                        <input type="text" name="name" value="<?= $msrf['requestor_name']; ?>" class="form-control select2" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Department</label>
			                                        <input type="text" name="department_description" id="department_description" value="<?= $msrf['department']; ?>" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                    </div>
			                                </div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Date Requested</label>
			                                        <input type="date" name="date_req" id="date_req" class="form-control select2" value="<?= $msrf['date_requested']; ?>" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_need" class="form-control select2" value="<?= $msrf['date_needed']; ?>" style="width: 100%;" readonly>
			                                    </div>
			                                </div>

                                            <!-- ASSET CODE START -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Asset Code</label>
                                                    <input type="text" name="asset_code" class="form-control select2" value="<?php echo $msrf['asset_code']; ?>" style="width: 100%;" placeholder="Asset Code" readonly>
                                                </div>
                                            </div>
                                            <!-- ASSET CODE END -->

                                            <!-- REQUEST CATEGORY START -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Request Category</label>
                                                    <select class="form-control select2" name="category" id="category" style="width: 100%;" disabled>
                                                        <option value="">Select Category</option>
                                                        <option value="computer"<?php if ($msrf['category'] == 'computer') echo ' selected'; ?>>Computer (Laptop or Desktop)</option>
                                                        <option value="printer"<?php if ($msrf['category'] == 'printer') echo ' selected'; ?>>Printer Concerns</option>
                                                        <option value="network"<?php if ($msrf['category'] == 'network') echo ' selected'; ?>>Network or Internet connection</option>
                                                        <option value="projector"<?php if ($msrf['category'] == 'projector') echo ' selected'; ?>>Projector / TV Set-up</option>
                                                        <option value="others"<?php if ($msrf['category'] == 'others') echo ' selected'; ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>                                          
                                            <!-- REQUEST CATEGORY END -->

                                            <!-- SPECIFY START -->
                                            <div class="col-md-12" id="specify-container" style="<?php echo ($msrf['category'] == 'Others') ? '' : 'display: none;'; ?>">
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <input type="text" name="msrf_specify" id="msrf_specify" class="form-control" value="<?= $msrf['specify']; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- SPECIFY END -->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>                                            
                                                    <textarea class="form-control" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" disabled><?= $msrf['details_concern']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="approval_stat" style="width: 100%;" <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected') echo 'disabled'; ?>>
                                                        <option value=""disabled selected></option>
                                                        <option value="Approved"<?php if ($msrf['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>ICT Approval Status</label>
                                                    <select name="it_approval_stat" class="form-control select2" <?php if ($msrf['it_approval_status'] == 'Approved' || $msrf['it_approval_status'] == 'Rejected') echo 'disabled'; ?>>
                                                        <option value=""disabled selected></option>
                                                        <option value="Approved"<?php if ($msrf['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if ($user_details['dept_id'] == 1) { ?>
                                                <?php if ($msrf['status'] == 'Resolved') {?>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Ticket Status</label>
                                                            <select name="it_status" id="it_status" class="form-control select2" <?php if ($msrf['status'] == 'On going' || $msrf['status'] == 'Resolved') echo 'disabled'; ?>>
                                                                <option value="">Please select status for reference</option>
                                                                <option value="On going" <?php if ($msrf['status'] == 'On going') echo 'selected'; ?>>On going</option>
                                                                <option value="Resolved" <?php if ($msrf['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($msrf['status'] == 'On going') { ?>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Ticket Status for Requestor</label>
                                                            <select name="status_users" id="status_users" class="form-control select2" <?php if ($msrf['status'] == 'Resolved') echo 'disabled'; ?>>
                                                                <option value="">Please select status for reference</option>
                                                                <option value="Resolved" <?php if ($msrf['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } else if ($msrf['status'] == 'Resolved') { ?>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>ICT Status Tickets</label>
                                                            <select name="status_users" id="status_users" class="form-control select2" <?php if ($msrf['status'] == 'Resolved') echo 'disabled'; ?>>
                                                                <option value="">Please select status for reference</option>
                                                                <option value="Resolved" <?php if ($msrf['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Ticket Status for Requestor</label>
                                                            <select name="status_requestor" id="status_requestor" class="form-control select2">
                                                                <option value="">Please select status for reference</option>
                                                                <option value="Closed">Closed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>

                                            <!-- REASON WHY REJECTED in db remarks_ict -->
                                            <div class="col-md-12" id="reason" style="<?php echo ($msrf['it_approval_status'] == 'Rejected') ? '' : 'display: none;'; ?>">
                                                <div class="form-group">
                                                    <label>Reason for Reject Tickets</label>
                                                    <textarea class="form-control" name="rejecttix" id="rejecttix" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled><?= $msrf['remarks_ict']; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- REASON WHY REJECTED in db remarks_ict -->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary" disabled>Submit Tickets</button>
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

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#it_approval_stat').change(function() {
        var status = $(this).val();
        if (status === 'Rejected') {
            $('#reason').show();
            $('#rejecttix').prop('disabled', false);
        } else {
            $('#reason').hide();
            $('#rejecttix').prop('disabled', true);
        }
    });
    
    $('#it_approval_stat').trigger('change');
});

/*$(document).ready(function() {
    $('#it_approval_stat').change(function() {
        var status = $(this).val();
        if (status === 'Approved') {
            $('#assigned-it-container').show();
        }else{
            $('#assigned-it-container').hide();
        }
    });

    $('#it_approval_stat').trigger('change');
});*/

$(document).ready(function() {
    // Temporarily enable the disabled dropdown
    $('#category').prop('disabled', false);
        $('#category').change(function() {
            var status = $(this).val();
            
            if (status === 'others') {  
                $('#specify-container').show();
            } else {
                $('#specify-container').hide();
            }
        });

    $('#category').trigger('change');
    $('#category').prop('disabled', true);
});


</script>