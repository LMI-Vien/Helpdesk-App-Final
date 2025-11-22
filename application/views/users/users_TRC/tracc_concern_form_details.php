<?php 
    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];
    $department_id = $sess_login_data['dept_id'];
    // print_r($sess_login_data);
    $disabled = "";
    $readonly = "";
    $btn_label = "Submit Ticket";
    if ($role === "L1") {
        $department_head_status = $tracc_con['approval_status'];    
        $status_tcf = $tracc_con['status'];
        // print_r($status_tcf);
        // die();

        if(($status_tcf === "In Progress" || $status_tcf === 'Approved' || $status_tcf === 'Done' || $status_tcf === 'Rejected' || $status_tcf === 'Closed' || $status_tcf === 'For Monitoring' || $status_tcf === 'For LSTV Concern')) {
            // echo "try";
            // die();
            $disabled = "disabled";
            $readonly = "readonly";
        } else {
            $disabled = "";
            $readonly = "";
            $btn_label = "Update Ticket";
        }
        $open_disabled = ($status_tcf === "Open" || $status_tcf === "Closed" || $status_tcf === "Returned") ? "disabled" : "";
    }
    // if($role === "L1" && $department_id === "1"){
    //     $department_status = $msrf['approval_status'];
    //         if($department_status === "Rejected" || $department_status === "Returned", || $department_status === "Approved"){
    //             $disabled = "disabled";
    //         }
    // }else{
    //     $disabled = "";
    // }
    
?>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Concern Details
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
                                        <form action="<?= site_url('UsersTraccCon_controller/acknowledge_as_resolved'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>Control Number</label>
                                                    <input type="text" name="control_number" id="control_number" class="form-control" value="<?= $tracc_con['control_number']; ?>" readonly>                                                 
			                    				</div>                                               
			                    			</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Module Affected</label>
                                                    <input type="text" name="module_affected" id="module_affected" class="form-control" value="<?= $tracc_con['module_affected']; ?>" <?=$readonly?>>
			                    				</div>                                                
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Company</label>
                                                    <select class="form-control select2" name="company" id="company" <?=$disabled?>>
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
                                                        <textarea class="form-control" name="concern" id="concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?=$readonly?>><?= $tracc_con['tcr_details']; ?></textarea>
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

                                            <div class="col-md-12" style="display: none;">
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
                                                    <input type="text" name="date_rep" id="date_rep" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat" disabled>
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
                                                    <label>ICT Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" disabled>
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($tracc_con['it_approval_status'] == 'Resolved') echo ' selected'; ?>>Resolved</option>
                                                        <option value="Closed"<?php if ($tracc_con['it_approval_status'] == 'Closed') echo ' selected'; ?>>Closed</option>
                                                        <option value="For Monitoring"<?php if ($tracc_con['it_approval_status'] == 'For Monitoring') echo ' selected'; ?>>For Monitoring</option>
                                                        <option value="For LSTV Concern"<?php if ($tracc_con['it_approval_status'] == 'For LSTV Concern') echo ' selected'; ?>>For LSTV Concern</option>

                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 40px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?= $disabled;?>><?= isset($tracc_con['reason_reject_tickets']) ? htmlspecialchars($tracc_con['reason_reject_tickets']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="returnedReason">
                                                <div class="form-group">
                                                    <label>Reason for Returned Ticket</label>
                                                    <textarea class="form-control" id="returnedReason" name="returnedReason" placeholder="Place the reason here" style="width: 100%; height: 40px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?= $disabled;?>><?= isset($tracc_con['returned_ticket_reason']) ? htmlspecialchars($tracc_con['returned_ticket_reason']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" disabled>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $tracc_con['resolved_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" disabled>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $tracc_con['resolved_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>  
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details<span style = "color:red;">*</span></label>
                                                    <textarea class="form-control" id="tcr_solution" name="tcr_solution" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly><?= isset($tracc_con['tcr_solution']) ? htmlspecialchars($tracc_con['tcr_solution']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" disabled>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <option value="HANNA" <?php if ($tracc_con['resolved_by'] == 'Hanna') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['resolved_by'] == 'Dan Mark') echo ' selected'; ?>>Sir. Dan</option>
                                                        <option value="CK" <?php if ($tracc_con['resolved_by'] == 'Calvin') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($tracc_con['resolved_by'] == 'Eric') echo ' selected'; ?>>Sir. Eric</option>
                                                        
                                                    </select>  
                                                </div>
                                            </div> -->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" disabled>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $tracc_con['resolved_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved Date <span style = "color:red;">*</span></label>
                                                    <input type="date" name="res_date" id="res_date" class="form-control select2" value="<?= $tracc_con['resolved_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Acknowledge as resolved by</label>
                                                    <input type="text" name="ack_as_res_by" id="ack_as_res_by" class="form-control" value="<?= $tracc_con['ack_as_resolved']; ?>" <?=$open_disabled?>>
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $tracc_con['ack_as_resolved_date']; ?>" style="width: 100%;" <?= ($status_tcf === 'Closed') ? 'disabled' : '' ?> <?=$open_disabled?>>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by ICT <span style = "color:red;">*</span></label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis" value="1"
                                                                    <?= isset($checkboxes['for_mis_concern']) && $checkboxes['for_mis_concern'] ? 'checked' : ''; ?> disabled> 
                                                                    For ICT Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst" value="1"
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
                                                                    <?= isset($checkboxes['user_error']) && $checkboxes['user_error'] ? 'checked' : ''; ?> disabled> 
                                                                    User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" id="received_by_lst_section" style="margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" value="<?= $tracc_con['received_by_lst']; ?>" class="form-control select2" placeholder="LST Coordinator" readonly>
                                                </div>

                                            </div>
                                            
                                            <div class="col-md-6">                            
                                                <div class="form-group">
                                                    <label>Others</label>
                                                    <input type="text" name="others" id="others" value="<?= $tracc_con['others']; ?>" class="form-control select2" placeholder="Please Specify" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6" id="date_section">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" id="date_lst" value="<?= $tracc_con['date_lst']; ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <input type="hidden" name="action" value="edit">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button type="submit" class="btn btn-primary" name="edit" <?=$disabled?>><?=$btn_label?></button>
                                                        <button type="submit" class="btn btn-success" name="acknowledge" onclick="setAcknowledgeFieldsRequired(); document.querySelector('[name=action]').value='acknowledge';" <?= ($status_tcf === 'Open' || $status_tcf === 'Rejected' || $status_tcf === 'Approved' || $status_tcf === 'Closed' || $status_tcf === 'Returned') ? 'disabled' : '' ?>>Acknowledge as Resolved</button>
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
        var reportedDate = "<?= $tracc_con['reported_date']; ?>";
        if (!reportedDate) {
            var today = new Date().toISOString().split('T')[0];
            $('#date_req').val();
        }

        let date_rep = new Date("<?= $tracc_con['reported_date'] ?>");
        let date_rep_date = date_rep.getDate().toString().padStart(2, '0');
        let date_rep_month = (date_rep.getMonth() + 1).toString().padStart(2, '0');
        $('#date_rep').val(`${date_rep_month}/${date_rep_date}/${date_rep.getFullYear()}`);

        toggleLstFields(); 
        $('#checkbox_lst').change(toggleLstFields);

        $("#reason_rejected_ticket").hide();
        $('#returnedReason').hide();

        checkApprovalStatus();
        checkReturnTicket();

        // Apply the resize function to the textarea on input
        $('#reason_rejected').on('input', autoResizeTextarea);
        $('#tcr_solution').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#reason_rejected').each(autoResizeTextarea);
        $('#tcr_solution').each(autoResizeTextarea);
    });

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

    function autoResizeTextarea() {
        $(this).css('height', 'auto');
        $(this).height(this.scrollHeight); 
    }

    function setAcknowledgeFieldsRequired() {
        // Get the acknowledge fields
        var ackAsResBy = document.getElementById('ack_as_res_by');
        var ackAsResDate = document.getElementById('ack_as_res_date');

        // Set both fields as required
        ackAsResBy.setAttribute('required', 'required');
        ackAsResDate.setAttribute('required', 'required');
    }

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


    function checkReturnTicket() {
        var appStatus = $('#app_stat').val();

        if (appStatus === 'Returned'){
            $("#returnedReason").show();
        } else {
            $("#returnedReason").hide();
        }
    }
    $('#app_stat').on('change', checkReturnTicket);

</script>