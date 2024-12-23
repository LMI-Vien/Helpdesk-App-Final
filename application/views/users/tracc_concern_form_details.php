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

        if(($status_tcf === "In Progress" || $status_tcf === 'Approved')) {
            // echo "try";
            // die();
            $disabled = "disabled";
            $readonly = "readonly";
            $btn_label = "Update Ticket";
        } else {
            $disabled = "";
            $readonly = "";
        }
            
        // if($status_tcf )
        
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
                                        <form action="<?= site_url('Main/acknowledge_as_resolved'); ?>" method="POST">
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
                                                    <input type="date" name="date_rep" id="date_rep" class="form-control select2" value="<?= $tracc_con['reported_date']; ?>" style="width: 100%;" readonly>
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

                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 40px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?= $disabled;?>><?= isset($tracc_con['reason_reject_tickets']) ? htmlspecialchars($tracc_con['reason_reject_tickets']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Received by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="received_by" id="received_by" value="" disabled>
                                                        <option value=""disabled selected>Received By</option>
                                                        <option value="HANNA" <?php if ($tracc_con['received_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['received_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                        <option value="CK" <?php if ($tracc_con['received_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($tracc_con['received_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>                                                       
                                                    </select>       
                                                </div>  
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Noted by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="noted_by" id="noted_by" value="" disabled>
                                                        <option value=""disabled selected>Noted By</option>
                                                        <option value="HANNA" <?php if ($tracc_con['noted_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option> 
                                                        <option value="DAN" <?php if ($tracc_con['noted_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>  
                                                        <option value="CK" <?php if ($tracc_con['noted_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($tracc_con['noted_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                          
                                                    </select>  
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details<span style = "color:red;">*</span></label>
                                                    <textarea class="form-control" id="tcr_solution" name="tcr_solution" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly><?= isset($tracc_con['tcr_solution']) ? htmlspecialchars($tracc_con['tcr_solution']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" disabled>
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <option value="HANNA" <?php if ($tracc_con['resolved_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($tracc_con['resolved_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                        <option value="CK" <?php if ($tracc_con['resolved_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($tracc_con['resolved_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        
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
                                                    <input type="text" name="ack_as_res_by" id="ack_as_res_by" class="form-control" value="<?= $tracc_con['ack_as_resolved']; ?>">
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $tracc_con['ack_as_resolved_date']; ?>" style="width: 100%;">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS <span style = "color:red;">*</span></label>
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
                                                        <button type="submit" class="btn btn-primary" name="edit">Update Changes</button>
                                                        <button type="submit" class="btn btn-success" name="acknowledge" onclick="setAcknowledgeFieldsRequired(); document.querySelector('[name=action]').value='acknowledge';">Acknowledge as Resolved</button>
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
            $('#date_req').val(today);
        }
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

    $(document).ready(function() {
        toggleLstFields(); 

        $('#checkbox_lst').change(toggleLstFields);
    });


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

    $(document).ready(function() {
        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        $('#reason_rejected').on('input', autoResizeTextarea);
        $('#tcr_solution').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#reason_rejected').each(autoResizeTextarea);
        $('#tcr_solution').each(autoResizeTextarea);
    });

    // $(document).on('click', '#form-add-submit-button', function(e) {
    //     e.preventDefault();
    //     var control_number = '<?= $this->uri->segment(6)?>';
    //     control_number = control_number.trim();
    //     var ict_approval = $('#it_app_stat').val();
    //     var reason_rejected = $('#reason_rejected').val();

    //     var data = {
    //         ict_approval: ict_approval,
    //         reason_rejected: reason_rejected,
    //         data_id: control_number,
    //         module:"tracc-concern"
    //     };

    //     $.ajax({
    //         url: base_url + "Main/update_ticket",
    //         type: "POST",
    //         data: data,
    //         success: function(response) {
    //             var response = JSON.parse(response);
    //             if (response.message === "success") {
    //                 location.href = '<?=base_url("sys/users/list/tickets/tracc_concern") ?>';
    //             } else {
    //                 //change this and add error message or redirect to main listing page
    //                 location.href = '<?=base_url("sys/users/list/tickets/tracc_concern") ?>';
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             //console.error("AJAX Error: " + error);
    //         }
    //     });
    // });

    function setAcknowledgeFieldsRequired() {
        // Get the acknowledge fields
        var ackAsResBy = document.getElementById('ack_as_res_by');
        var ackAsResDate = document.getElementById('ack_as_res_date');

        // Set both fields as required
        ackAsResBy.setAttribute('required', 'required');
        ackAsResDate.setAttribute('required', 'required');
    }

</script>