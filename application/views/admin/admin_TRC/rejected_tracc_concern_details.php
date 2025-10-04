<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Rejected TRACC Concern
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
			                <li class="active"><a href="#trccern" data-toggle="tab">Ticket for TRACC Concern</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="trccern">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('AdminTraccCon_controller/admin_list_trccern'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>Control Number</label>
                                                    <input type="text" name="control_number" id="control_number" class="form-control" value="<?= $trc['control_number']; ?>" readonly>                                                 
			                    				</div>                                               
			                    			</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Module Affected</label>
                                                    <input type="text" name="module_affected" id="module_affected" class="form-control" value="<?= $trc['module_affected']; ?>" readonly>
			                    				</div>                                                
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Company</label>
                                                    <select class="form-control select2" name="company" id="company" disabled>
                                                        <option value=""disabled selected>Company Category</option>
                                                        <option value="lmi"<?php if ($trc['company'] == 'LMI') echo ' selected'; ?>>LMI</option>
                                                        <option value="rgdi"<?php if ($trc['company'] == 'RGDI') echo ' selected'; ?>>RGDI</option>
                                                        <option value="lpi"<?php if ($trc['company'] == 'LPI') echo ' selected'; ?>>LPI</option>
                                                        <option value="sv"<?php if ($trc['company'] == 'SV') echo ' selected'; ?>>SV</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                        <textarea class="form-control" name="concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"disabled><?= $trc['tcr_details']; ?></textarea>
                                                </div>
                                            </div>

                                            <!-- New Section for File Display -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>View File</label><br>
                                                    <?php if (!empty($trc['file'])): ?>
                                                        <a href="<?= site_url('uploads/trccern/' . $trc['file']); ?>" target="_blank" class="btn btn-primary">
                                                            <i class="fa fa-eye"></i> View Uploaded File
                                                        </a>
                                                    <?php else: ?>
                                                        <div class="alert alert-light" role="alert">
                                                            <i class="fa fa-exclamation-circle"></i> No file uploaded.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
			                    					<label>Priority</label>
                                                    <select class="form-control select2" name="priority" id="priority" disabled readonly>
                                                        <option value=""disabled selected>Priority</option>
                                                        <option value="Low"<?php if ($trc['priority'] == 'Low') echo ' selected'; ?>>Low</option>
                                                        <option value="Medium"<?php if ($trc['priority'] == 'Medium') echo ' selected'; ?>>Medium</option>
                                                        <option value="High"<?php if ($trc['priority'] == 'High') echo ' selected'; ?>>High</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Reported by</label>
                                                    <input type="text" name="name" value="<?= $trc['reported_by']; ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Reported</label>
                                                    <input type="date" name="date_rep" id="date_rep" class="form-control select2" value="<?= $trc['reported_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat"  disabled readobly>
                                                    <?php if ($trc['approval_status'] == 'Approved' || $trc['approval_status'] == 'Rejected') echo 'disabled'; ?>
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($trc['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($trc['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($trc['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Returned"<?php if ($trc['approval_status'] == 'Returned') echo ' selected'; ?>>Returned</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ICT Approval Status</label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" disabled readonly>
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($trc['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($trc['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($trc['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($trc['it_approval_status'] == 'Resolved') echo ' selected'; ?>>Done</option>
                                                        <option value="Closed"<?php if ($trc['it_approval_status'] == 'Closed') echo ' selected'; ?>>Closed</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket" style="display: none;">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"><?= isset($trc['reason_reject_tickets']) ? htmlspecialchars($trc['reason_reject_tickets']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Received by</label>
                                                    <select class="form-control select2" name="received_by" id="received_by">
                                                        <option value=""disabled selected>Received By</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $trc['received_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Noted by</label>
                                                    <select class="form-control select2" name="noted_by" id="noted_by">
                                                        <option value=""disabled selected>Noted By</option>   
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $trc['noted_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details</label>
                                                    <textarea class="form-control" id="tcr_solution" name="tcr_solution" placeholder="Place the details concern here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" disabled><?= isset($trc['tcr_solution']) ? htmlspecialchars($trc['tcr_solution']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by">
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $trc['resolved_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved Date</label>
                                                    <input type="date" name="res_date" id="res_date" class="form-control select2" value="<?= $trc['resolved_date']; ?>" style="width: 100%;" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Acknowledge as resolved by</label>
                                                    <input type="text" name="ack_as_res" id="ack_as_res" class="form-control" value="<?= $trc['ack_as_resolved']; ?>" readonly>
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $trc['ack_as_resolved_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>
                                                            
                                            <!-- Checkbox -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS</label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis" value="1"
                                                                    <?= isset($checkboxes['for_mis_concern']) && $checkboxes['for_mis_concern'] ? 'checked' : ''; ?> disabled> 
                                                                    For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst" value="1" onclick="toggleLstFields()"
                                                                    <?= isset($checkboxes['for_lst_concern']) && $checkboxes['for_lst_concern'] ? 'checked' : ''; ?> disabled> 
                                                                    For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error" value="1"
                                                                    <?= isset($checkboxes['system_error']) && $checkboxes['system_error'] ? 'checked' : ''; ?> disabled> 
                                                                    System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error" value="1"
                                                                    <?= isset($checkboxes['user_error']) && $checkboxes['user_error'] ? 'checked' : ''; ?> disabled> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Received by and Date fields (initially hidden) -->
                                                <div class="form-group" id="received_by_lst_section" style="display:none; margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" id="received_by_lst" value="<?= $trc['received_by_lst']; ?>" class="form-control select2" placeholder="LST Coordinator">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">                            
                                                <div class="form-group">
                                                    <label>Others</label>
                                                    <input type="text" name="others" id="others" value="<?= $trc['others']; ?>" class="form-control select2" placeholder="Please Specify" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6" id="date_section">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" id="date_lst" value="<?= $trc['date_lst']; ?>" class="form-control select2">
                                                </div>
                                            </div>
                                        </form>
                                        <!-- <?= $trc['control_number'] ?> -->
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

<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var reportedDate = "<?= isset($trc['reported_date']) ? $trc['reported_date'] : ''; ?>";
        var resolutionDate = "<?= isset($trc['resolved_date']) ? $trc['resolved_date'] : ''; ?>";
        
        if (!reportedDate){
            var today = new Date().toISOString().split('T')[0];
            $('#date_rep').val(today);
        }

        if (!resolutionDate){
            var today = new Date().toISOString().split('T')[0];
            $('#res_date').val(today);
        }

        // Checking of Checkbox if it has value (FOR LST CONCERN) - start
        function toggleLstFields() {
            var lstCheckbox = $('#checkbox_lst');
            var receivedBySection = $('#received_by_lst_section');
            var dateSection = $('#date_section');

            if (lstCheckbox.is(':checked')) {
                receivedBySection.show();
                dateSection.show();
            } else {
                receivedBySection.hide();
                dateSection.hide();
            }
        }

        toggleLstFields(); 
        $('#checkbox_lst').change(toggleLstFields);
        // Checking of Checkbox if it has value (FOR LST CONCERN) - end


        // Showing of Rejected Ticket - start
        $("#reason_rejected_ticket").hide();
        
        function checkApprovalStatus() {
            var itApprovalStatus = $('#it_app_stat').val();
            var appStatus = $('#app_stat').val();

            if (itApprovalStatus === 'Rejected' || appStatus === 'Rejected'){
                $("#reason_rejected_ticket").show();
            } else {
                $("#reason_rejected_ticket").hide();
            }
        }

        $('#it_app_stat, #app_stat').on('change', checkApprovalStatus);
        checkApprovalStatus();
        // Showing of Rejected Ticket - end


        // Dynamic Rezising of tcr_solution - start
        function autoResizeTextarea() {
            $(this).css('height', 'auto'); 
            $(this).height(this.scrollHeight); 
        }
    
        $('#tcr_solution').on('input', autoResizeTextarea);
        $('#tcr_solution').each(autoResizeTextarea);
        // Dynamic Rezising of tcr_solution - end 
   

        // ICT Approval Disable field - start
        function ICTApproval() {
            var ictApprovalStatus = $('#it_app_stat').val(); 
            
            if (ictApprovalStatus === 'Approved') {
                $('#received_by').prop('disabled', false);
                $('#noted_by').prop('disabled', false);
                $('#tcr_solution').prop('readonly', false);
                $('#resolved_by').prop('disabled', false);
                $('#res_date').prop('disabled', false);
                $('#others').prop('disabled', false);
                $('#checkbox_mis').prop('disabled', false);
                $('#checkbox_lst').prop('disabled', false);
                $('#checkbox_system_error').prop('disabled', false);
                $('#checkbox_user_error').prop('disabled', false);
                $('#received_by_lst').prop('disabled', false);
                $('#date_lst').prop('disabled', false);
            } else {
                $('#received_by').prop('disabled', true); 
                $('#noted_by').prop('disabled', true);  
                $('#tcr_solution').prop('readonly', true); 
                $('#resolved_by').prop('disabled', true);
                $('#res_date').prop('disabled', true);
                $('#others').prop('disabled', true);
                $('#checkbox_mis').prop('disabled', true);
                $('#checkbox_lst').prop('disabled', true);
                $('#checkbox_system_error').prop('disabled', true);
                $('#checkbox_user_error').prop('disabled', true);
                $('#received_by_lst').prop('disabled', true);
                $('#date_lst').prop('disabled', true);
                $('#reason_rejected').prop('disabled',true);
            }
        }

        ICTApproval();
        $('#it_app_stat').on('change', ICTApproval);
        // ICT Approval Disable field - end

    });
        

</script>