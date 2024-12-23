<div class="content-wrapper">
    <section class="content-header">
		<h1>
			MSRF Approval Tickets
			<small>Ticket</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-users"></i> Home</a></li>
			<li class="active">My Tickets</li>
			<li class="active">Creation Tickets</li>
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
			                    <form action="<?= site_url('Main/admin_list_tickets'); ?>" method="POST">
			                    	<div class="col-md-12">
			                    		<div class="form-group">
			                    			<label>MSRF#</label>
			                    			<input type="text" name="msrf_number" id="msrf_number" value="<?php echo $msrf['ticket_id']; ?>" class="form-control" readonly>
			                    		</div>
			                    	</div>
			                    	<div class="col-md-6">
			                            <div class="form-group">
			                                <label>Requestor</label>
			                                <input type="text" name="name" class="form-control" value="<?php echo $msrf['requestor_name']; ?>" readonly>
			                            </div>
			                            <div class="form-group">
			                                <label>Department</label>
												<input type="text" name="department_description" id="department_description" value="<?php echo $msrf['department']; ?>" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                <label>Date Requested</label>
			                                <input type="date" name="date_req" id="date_req" class="form-control select2" value="<?php echo $msrf['date_requested']; ?>" style="width: 100%;" readonly>
			                            </div>
			                            <div class="form-group">
			                                <label>Date Needed</label>
			                                <input type="date" name="date_need" class="form-control select2" value="<?php echo $msrf['date_needed']; ?>" style="width: 100%;" readonly>
			                            </div>
			                        </div>
			                        <div class="col-md-12">
			                            <div class="form-group">
			                                <label>Asset Code</label>
			                                <input type="text" name="asset_code" class="form-control select2" value="<?php echo $msrf['asset_code']; ?>" style="width: 100%;" placeholder="Asset Code" readonly>
			                            </div>
			                        </div>
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
												<textarea class="form-control" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" disabled><?php echo $msrf['details_concern']; ?></textarea>
			                            </div>
			                        </div>
									<div class="col-md-12">
			                            <div class="form-group">
			                                <label>Approval Status</label>											
											<select class="form-control select2" name="approval_stat" style="width: 100%;" <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected') echo 'disabled'; ?>>
												<option value="" disabled selected>Select Approval</option>
												<option value="Approved"<?php if ($msrf['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
												<option value="Pending"<?php if ($msrf['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
												<option value="Rejected"<?php if ($msrf['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
											</select>
			                            </div>
			                        </div>

									<?php if ($user_details['dept_id'] == 1) { ?>
										<div class="col-md-12">
											<div class="form-group">
												<label>ICT Approval Status</label>
												<select class="form-control select2" name="it_approval_stat" id="it_approval_stat" style="width: 100%;" <?php if ($msrf['it_approval_status'] == 'Approved'); ?>>
													<option value="" disabled selected>Select ICT Approval</option>
													<option value="Approved" <?php if ($msrf['it_approval_status'] == 'Approved') echo 'selected'; ?>>Approved</option>
													<option value="Rejected" <?php if ($msrf['it_approval_status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
													<option value="Resolved" <?php if ($msrf['it_approval_status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
												</select>
											</div>
										</div>
										<div class="col-md-12" id="ictassign" style="display: none;">
											<div class="form-group">
												<label>ICT Assign To</label>
												<select name="assign_to" class="form-control select2">
													<option value="" disabled selected>Select ICT</option>
													<?php if (isset($getTeam) && is_array($getTeam)) : ?>
														<?php foreach($getTeam as $team) : ?>
															<?php if (is_array($team)) : // Ensure $team is an array ?>
																<option value="<?php echo $team['emp_id']; ?>">
																	<!-- eto yung gagalawin -->
																	<?php echo $team['fname'] . ' ' . $team['lname']; ?>
																</option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
										</div>
									<?php } else { ?>
										<?php if ($msrf['it_approval_status'] == "Resolveded") { ?>
											<div class="col-md-12">
												<div class="form-group">
													<label>Status</label>
													<select name="status" class="form-control select2" <?php if ($msrf['status'] == 'Closed') echo 'disabled'; ?>>
														<option value="">Please select status for reference</option>
														<option value="Closed" <?php if ($msrf['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
													</select>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
									
										<!-- REJECTED TIX -->
										<div class="col-md-12" id="reason" style="">
											<div class="form-group">
												<label>Reason for Reject Tickets</label>
												<textarea class="form-control" name="rejecttix" id="rejecttix" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; text-align: left; resize: vertical;"><?= isset($msrf['remarks_ict']) ? htmlspecialchars($msrf['remarks_ict']) : ''; ?></textarea>
											</div>
										</div>
										<!-- REJECTED TIX -->
										
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <div class="box-body pad">
			                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Validate Ticket</button>
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

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

<script>
	$(document).ready(function() {
		$("#reason").hide();

		$('#it_approval_stat').on('change', function() {
			var approvalStatus = $(this).val();
			if (approvalStatus === 'Rejected') {
				$("#reason").show();  // Show the reason textarea
			} else {
				$("#reason").hide();  // Hide the reason textarea
			}
		});

		// Trigger the change event to handle the case where the page is loaded with "Rejected" already selected
		$('#it_approval_stat').trigger('change');
	});

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

	/*$(document).ready(function() {
		$('#it_approval_stat').change(function() {
			var statusapp = $(this).val();

			if (statusapp === 'Approved') {
				$('#ictassign').show();
				$(this).prop('disabled', true);
			} else {
				$('#ictassign').hide();
			}
		});

		$('#it_approval_stat').trigger('change');
	});*/

</script>