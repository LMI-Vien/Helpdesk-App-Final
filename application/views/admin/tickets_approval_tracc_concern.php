<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Concern Approval
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
			                <li class="active"><a href="#tracc_concern" data-toggle="tab">Ticket for TRACC Concern</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tracc_concern">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/admin_list_tracc_concern'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>Control Number</label>
                                                    <input type="text" name="control_number" id="control_number" class="form-control" value="<?= $tracc_con['control_number']; ?>" readonly>                                                 
			                    				</div>                                               
			                    			</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Module Affected</label>
                                                    <input type="text" name="module_affected" id="module_affected" class="form-control" value="<?= $tracc_con['module_affected']; ?>" readonly>
			                    				</div>                                                
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Company</label>
                                                    <select class="form-control select2" name="company" id="company" disabled>
                                                        <option value=""disabled selected>Company Category</option>
                                                        <option value="lmi"<?php if ($tracc_con['company'] == 'LMI') echo ' selected'; ?>>LMI</option>
                                                        <option value="rgdi"<?php if ($tracc_con['company'] == 'RGDI') echo ' selected'; ?>>RGDI</option>
                                                        <option value="lpi"<?php if ($tracc_con['company'] == 'LPI') echo ' selected'; ?>>LPI</option>
                                                        <option value="sv"<?php if ($tracc_con['company'] == 'SV') echo ' selected'; ?>>SV</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                        <textarea class="form-control" name="concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"disabled><?= $tracc_con['tcr_details']; ?></textarea>
                                                </div>
                                            </div>

                                            <!-- New Section for File Display -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>View File</label><br>
                                                    <?php if (!empty($tracc_con['file'])): ?>
                                                        <a href="<?= site_url('uploads/tracc_concern/' . $tracc_con['file']); ?>" target="_blank" class="btn btn-primary">
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
                                                    <select class="form-control select2" name="priority" id="priority">
                                                        <option value=""disabled selected>Priority</option>
                                                        <option value="Low"<?php if ($tracc_con['priority'] == 'Low') echo ' selected'; ?>>Low</option>
                                                        <option value="Medium"<?php if ($tracc_con['priority'] == 'Medium') echo ' selected'; ?>>Medium</option>
                                                        <option value="High"<?php if ($tracc_con['priority'] == 'High') echo ' selected'; ?>>High</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Reported by</label>
                                                    <input type="text" name="name" value="<?= $tracc_con['reported_by']; ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Reported</label>
                                                    <input type="date" name="date_rep" id="date_rep" class="form-control select2" value="<?= $tracc_con['reported_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <?php
                                            $user_role = $this->session->userdata('user_role'); // Fetch user role from session
                                            $is_disabled = ($user_role == 'L3') ? 'disabled' : ''; // Set disabled attribute if role is l3
                                            $is_disable = ($user_role == 'L2') ? 'disabled' : '';
                                            ?>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat" <?php if ($tracc_con['approval_status'] == 'Approved' || $tracc_con['approval_status'] == 'Rejected') echo 'disabled'; ?> <?= $is_disable?>>
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Returned"<?php if ($tracc_con['approval_status'] == 'Returned') echo ' selected'; ?>>Returned</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ICT Approval Status</label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" <?= $is_disabled; ?> >
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($tracc_con['it_approval_status'] == 'Resolved') echo ' selected'; ?>>Done</option>
                                                        <option value="Closed"<?php if ($tracc_con['it_approval_status'] == 'Closed') echo ' selected'; ?>>Closed</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket" style="display: none;">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"><?= isset($tracc_con['reason_reject_tickets']) ? htmlspecialchars($tracc_con['reason_reject_tickets']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Received by</label>
                                                    <select class="form-control select2" name="received_by" id="received_by" <?= $is_disabled; ?>>
                                                        <option value=""disabled selected>Received By</option>
                                                        <option value="ERIC" <?php if ($tracc_con['received_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        <option value="CK" <?php if ($tracc_con['received_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="HANNA" <?php if ($tracc_con['received_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['received_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Noted by</label>
                                                    <select class="form-control select2" name="noted_by" id="noted_by" <?= $is_disabled; ?>>
                                                        <option value=""disabled selected>Noted By</option>
                                                        <option value="ERIC" <?php if ($tracc_con['noted_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        <option value="CK" <?php if ($tracc_con['noted_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="HANNA" <?php if ($tracc_con['noted_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['noted_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>    
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details</label>
                                                    <textarea class="form-control" id="tcr_solution" name="tcr_solution" placeholder="Place the details concern here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?= $is_disabled; ?>><?= isset($tracc_con['tcr_solution']) ? htmlspecialchars($tracc_con['tcr_solution']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" <?= $is_disabled; ?>>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <option value="ERIC" <?php if ($tracc_con['resolved_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        <option value="CK" <?php if ($tracc_con['resolved_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="HANNA" <?php if ($tracc_con['resolved_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['resolved_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved Date</label>
                                                    <input type="date" name="res_date" id="res_date" class="form-control select2" value="<?= $tracc_con['resolved_date']; ?>" style="width: 100%;" <?= $is_disabled; ?>>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Acknowledge as resolved by</label>
                                                    <input type="text" name="ack_as_res" id="ack_as_res" class="form-control" value="<?= $tracc_con['ack_as_resolved']; ?>" readonly>
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $tracc_con['ack_as_resolved_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS</label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis"> For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst"> For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error"> System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error"> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" value="" class="form-control select2" placeholder="LST Coordinator">
                                                </div>

                                            </div>-->
                                                            
                                            <!-- Checkbox -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS</label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis" value="1"
                                                                    <?= isset($checkboxes['for_mis_concern']) && $checkboxes['for_mis_concern'] ? 'checked' : ''; ?> <?= $is_disabled; ?>> 
                                                                    For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst" value="1" onclick="toggleLstFields()"
                                                                    <?= isset($checkboxes['for_lst_concern']) && $checkboxes['for_lst_concern'] ? 'checked' : ''; ?> <?= $is_disabled; ?>> 
                                                                    For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error" value="1"
                                                                    <?= isset($checkboxes['system_error']) && $checkboxes['system_error'] ? 'checked' : ''; ?> <?= $is_disabled; ?>> 
                                                                    System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error" value="1"
                                                                    <?= isset($checkboxes['user_error']) && $checkboxes['user_error'] ? 'checked' : ''; ?> <?= $is_disabled; ?>> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Received by and Date fields (initially hidden) -->
                                                <div class="form-group" id="received_by_lst_section" style="display:none; margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" id="received_by_lst" value="<?= $tracc_con['received_by_lst']; ?>" class="form-control select2" placeholder="LST Coordinator">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">                            
                                                <div class="form-group">
                                                    <label>Others</label>
                                                    <input type="text" name="others" id="others" value="<?= $tracc_con['others']; ?>" class="form-control select2" placeholder="Please Specify" <?= $is_disabled; ?>>
                                                </div>
                                            </div>

                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" value="" class="form-control select2">
                                                </div>
                                            </div>-->

                                            <div class="col-md-6" id="date_section">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" id="date_lst" value="<?= $tracc_con['date_lst']; ?>" class="form-control select2">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Validate Tickets</button>
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

<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var reportedDate = "<?= isset($tracc_con['reported_date']) ? $tracc_con['reported_date'] : ''; ?>";
        var resolutionDate = "<?= isset($tracc_con['resolved_date']) ? $tracc_con['resolved_date'] : ''; ?>";
        
        if (!reportedDate){
            var today = new Date().toISOString().split('T')[0];
            $('#date_rep').val(today);
        }

        if (!resolutionDate){
            var today = new Date().toISOString().split('T')[0];
            $('#res_date').val(today);
        }
    });

    //checking of checkbox if it has a value
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

    $(document).ready(function() {
        toggleLstFields(); 

        $('#checkbox_lst').change(toggleLstFields);
    });


    // showing of rejected ticket
    $(document).ready(function() {
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
        
    });

    //dynamic rezising of the tcr_solution
    $(document).ready(function() {
        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        //$('#reason_rejected').on('input', autoResizeTextarea);
        $('#tcr_solution').on('input', autoResizeTextarea);

        // Trigger the resize on page load if there's existing content in the textarea
        //$('#reason_rejected').each(autoResizeTextarea);
        $('#tcr_solution').each(autoResizeTextarea);
    });

     
    $(document).ready(function() { 
        function ICTApproval() {
            var ictApprovalStatus = $('#it_app_stat').val(); // Get the value of the ICT Approval Status dropdown
            
            if (ictApprovalStatus === 'Approved') {
                $('#received_by').prop('disabled', false); // Enable Received by field
                $('#noted_by').prop('disabled', false); // Enable Noted by field
                $('#tcr_solution').prop('readonly', false); // Enable Solution/Details textarea
                $('#resolved_by').prop('disabled', false);
                $('#res_date').prop('disabled', false);
                $('#others').prop('disabled', false);
                $('#checkbox_mis').prop('disabled', false);
                $('#checkbox_lst').prop('disabled', false);
                $('#checkbox_system_error').prop('disabled', false);
                $('#checkbox_user_error').prop('disabled', false);
            } else {
                $('#received_by').prop('disabled', true);  // Disable Received by field
                $('#noted_by').prop('disabled', true);  // Disable Noted by field
                $('#tcr_solution').prop('readonly', true); // Make Solution/Details textarea readonly
                $('#resolved_by').prop('disabled', true);
                $('#res_date').prop('disabled', true);
                $('#others').prop('disabled', true);
                $('#checkbox_mis').prop('disabled', true);
                $('#checkbox_lst').prop('disabled', true);
                $('#checkbox_system_error').prop('disabled', true);
                $('#checkbox_user_error').prop('disabled', true);
            }
        }

        // Call the function when the page loads to check the initial state
        ICTApproval();

        // Bind the change event to the ICT Approval Status dropdown
        $('#it_app_stat').on('change', function() {
            ICTApproval(); // Toggle the fields based on the new ICT Approval Status selection
        });
    });

</script>