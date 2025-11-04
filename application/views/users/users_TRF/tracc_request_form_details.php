<?php 
    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];
    $department_id = $sess_login_data['dept_id'];
    // print_r($sess_login_data);
    $disabled = "";
    $readonly = "";
    $btn_label = "Submit Ticket";
    if ($role === "L1") {
        $department_head_status = $trf['approval_status'];
        
        $status_trf = $trf['status'];
        // print_r($status_trf);
        // die();

        if(($status_trf === "In Progress" || $status_trf === 'Approved' || $status_trf === 'Resolved' || $status_trf === 'Rejected' || $status_trf === 'Closed')) {
            // echo "try";
            // die();
            $disabled = "disabled";
            $readonly = "readonly";
        } else {
            $disabled = "";
            $readonly = "";
            $btn_label = "Update Ticket";
        }
        // $open_disabled = ($status_trf === "Open") ? "disabled" : "";
        $open_disabled = in_array($status_trf, ['Open', 'Returned'], true) ? 'disabled' : '';
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

<style>
    .custom-checkbox {
        display: inline-flex;
        align-items: center; /* Centers checkbox and label vertically */
        margin: 0 20px; /* Space between each checkbox item */
    }

    .custom-checkbox input[type="checkbox"] {
        width: 20px;
        height: 18px; /* Larger checkbox size */
        margin: 0; /* Reset any default margin */
    }

    .checkbox-label {
        font-size: 22px; /* Larger label text */
        margin-left: 25px; /* Space between checkbox and label text */
        line-height: 2; /* Ensure label text aligns vertically */
    }

    .divider {
        height: 2px; /* Height of the line */
        background-color: #ccc; /* Color of the line */
        margin: 10px 0; /* Space above and below the line */
        width: 99%; /* Adjust width as needed */
        margin-left: auto; /* Center the line */
        margin-right: auto; /* Center the line */
    }

    .design-text {
        font-size: 20px; /* Adjust font size as needed */
        font-weight: ; /* Make the text bold */
        margin-top: 10px; /* Space above the text */
        text-align: center;
        width: 100%; /* Ensure the text container takes the full width */
    }

    .textarea-wrapper {
        position: relative; /* Make the wrapper position relative */
    }

    .placeholder-text {
        position: absolute;
        left: 10px; /* Aligns to the left */
        bottom: 10px; /* Adjusts vertical position */
        color: #999; /* Placeholder color */
        pointer-events: none; /* Makes sure clicks go through to the textarea */
        transition: opacity 0.2s ease; /* Optional transition for smoother appearance */
    }

    /* Hide the placeholder when the textarea has value */
    textarea:focus + .placeholder-text,
    textarea:not(:placeholder-shown) + .placeholder-text {
        opacity: 0; /* Hide when focused or has text */
    }

    .circle-checkbox input[type="checkbox"] {
        display: none; /* Hide the default checkbox */
    }

    .circle-checkbox label {
        position: relative;
        padding-left: 30px; /* Space for the custom circle */
        cursor: pointer;
        font-size: 16px;
        color: #333; /* Text color */
        font-weight: normal;
    }

    .circle-checkbox label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 20px; /* Circle size */
        height: 20px; /* Circle size */
        border: 2px solid #000; /* Border color */
        border-radius: 50%; /* Make it a circle */
        background-color: #fff; /* Background color */
    }

    .circle-checkbox input[type="checkbox"]:checked + label:before {
        background-color: #000; /* Dark fill when checked */
        border-color: #000; /* Darken border when checked */
    }

    .circle-checkbox input[type="checkbox"]:checked + label {
        color: #000; /* Text color on check */
    }

    /* Style for the "Others" input field beside the checkbox */
    .circle-checkbox input[type="text"] {
        border: none;
        border-bottom: 1px solid #000;
        background: none;
        min-width: 150px;
        padding-left: 5px;
        font-size: 16px;
    }
</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Request Details
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">TRACC Request Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Ticket for TRACC Request</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('UsersTraccReq_controller/update_status_tracc_request'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>TRF#</label>
                                                    <input type="text" name="trf_number" id="trf_number" class="form-control" value="<?= $trf['ticket_id']; ?>" readonly>
			                    				</div>                                               
			                    			</div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Requested_by</label>
                                                    <input type="text" name="name" value="<?= $trf['requested_by']; ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Requested</label>
                                                    <input type="text" name="date_requested" id="date_requested" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Department</label>
                                                        <input type="text" name="department_description" id="department_description" value="<?= $trf['department']; ?>" class="form-control select2" style="width: 100%;" readonly/>
                                                    <input type="hidden" name="dept_id" value="">
                                                    <input type="hidden" name="sup_id" value="">
			                                    </div> 
                                            </div>   

                                            <div class="col-md-6">
                                                <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_need" id="date_need" value="<?= $trf['date_needed']; ?>" class="form-control select2" style="width: 100%;" min="<?= date('Y-m-d'); ?>" <?=$readonly?>>
			                                    </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <div class="form-group d-flex justify-content-center">
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox"  name="comp_checkbox_value[]" id="checkbox_lmi" value="LMI" <?php echo (in_array('LMI', $selected_companies)) ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" id="checkbox_rgdi" value="RGDI" <?php echo (in_array('RGDI', $selected_companies)) ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" id="checkbox_lpi" value="LPI" <?php echo (in_array('LPI', $selected_companies)) ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" id="checkbox_sv" value="SV" <?php echo (in_array('SV', $selected_companies)) ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label for="checkbox_sv" class="checkbox-label">SV</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <hr class="divider"> 
                                            
                                            <div class="design-text">
                                                <b>Master File / Tracc Access</b>
                                            </div>

                                            <hr class="divider"> 
                                            
                                            <div class="row">
                                                <div class="col-md-6 text-left">
                                                    <div class="form-group d-flex flex-column align-items-center" style="margin-left: 80px;">
                                                        <label for="" style="font-size: 21px;">New/Add</label>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_item" id="checkbox_item" <?php echo isset($checkbox_data_newadd['item']) && $checkbox_data_newadd['item'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_item">Item</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer" id="checkbox_customer" <?php echo isset($checkbox_data_newadd['customer']) && $checkbox_data_newadd['customer'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                            <label for="checkbox_customer">Customer</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier" id="checkbox_supplier" <?php echo isset($checkbox_data_newadd['supplier']) && $checkbox_data_newadd['supplier'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_supplier">Supplier</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_whs" id="checkbox_whs" <?php echo isset($checkbox_data_newadd['warehouse']) && $checkbox_data_newadd['warehouse'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_whs">Warehouse</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_bin" id="checkbox_bin" <?php echo isset($checkbox_data_newadd['bin_number']) && $checkbox_data_newadd['bin_number'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_bin">Bin No.</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_cus_ship_setup" id="checkbox_cus_ship_setup" <?php echo isset($checkbox_data_newadd['customer_shipping_setup']) && $checkbox_data_newadd['customer_shipping_setup'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_cus_ship_setup">Customer Shipping Setup</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_employee_req_form" id="checkbox_employee_req_form" <?php echo isset($checkbox_data_newadd['employee_request_form']) && $checkbox_data_newadd['employee_request_form'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_employee_req_form">Employee Request Form</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_newadd" id="checkbox_others_newadd" <?php echo isset($checkbox_data_newadd['others']) && $checkbox_data_newadd['others'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_others_newadd">Others</label><br>

                                                            <input type="text" name="others_text_newadd" id="others_text_newadd" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_data_newadd['others_description_add'] ?>" <?=$disabled?>>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 text-left" style="margin-left: -150px;">
                                                    <div class="form-group d-flex flex-column align-items-center">
                                                        <label for="" style="font-size: 21px;">Update</label>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_system_date_lock" id="checkbox_system_date_lock" <?php echo isset($checkbox_data_update['system_date_lock']) && $checkbox_data_update['system_date_lock'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_system_date_lock">System Date Lock</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_user_file_access" id="checkbox_user_file_access" <?php echo isset($checkbox_data_update['user_file_access']) && $checkbox_data_update['user_file_access'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_user_file_access">User File Access</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_item_dets" id="checkbox_item_dets" <?php echo isset($checkbox_data_update['item_details']) && $checkbox_data_update['item_details'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_item_dets">Item Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer_dets" id="checkbox_customer_dets" <?php echo isset($checkbox_data_update['customer_details']) && $checkbox_data_update['customer_details'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_customer_dets">Customer Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier_dets" id="checkbox_supplier_dets" <?php echo isset($checkbox_data_update['supplier_details']) && $checkbox_data_update['supplier_details'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_supplier_dets">Supplier Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_employee_dets" id="checkbox_employee_dets" <?php echo isset($checkbox_data_update['employee_details']) && $checkbox_data_update['employee_details'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_employee_dets">Employee Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_update" id="checkbox_others_update" <?php echo isset($checkbox_data_update['others']) && $checkbox_data_update['others'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_others_update">Others</label><br>

                                                            <input type="text" name="others_text_update" id="others_text_update" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_data_update['others_description_update'] ?>" <?=$disabled?>>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-left" style="margin-left: -250px;">
                                                    <div class="form-group d-flex flex-column align-items-start">
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_tracc_orien" id="checkbox_tracc_orien" <?php echo isset($checkbox_data_account['tracc_orientation']) && $checkbox_data_account['tracc_orientation'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_tracc_orien">Tracc Orientation</label>
                                                        </div>
                                                        <label for="" style="font-size: 21px;">Create TRACC Account</label>
                                                        
                                                        <!-- Use CSS Grid for precise alignment -->
                                                        <div class="d-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; width: 100%;">
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lmi" id="checkbox_create_lmi" <?php echo isset($checkbox_data_account['lmi']) && $checkbox_data_account['lmi'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                                <label for="checkbox_create_lmi">LMI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lpi" id="checkbox_create_lpi" <?php echo isset($checkbox_data_account['lpi']) && $checkbox_data_account['lpi'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                                <label for="checkbox_create_lpi">LPI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_rgdi" id="checkbox_create_rgdi" <?php echo isset($checkbox_data_account['rgdi']) && $checkbox_data_account['rgdi'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                                <label for="checkbox_create_rgdi">RGDI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_sv" id="checkbox_create_sv" <?php echo isset($checkbox_data_account['sv']) && $checkbox_data_account['sv'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                                <label for="checkbox_create_sv">SV</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_gps_account" id="checkbox_gps_account" <?php echo isset($checkbox_data_account['gps_account']) && $checkbox_data_account['gps_account'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_gps_account">GPS Account</label>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others" id="checkbox_others"  <?php echo isset($checkbox_data_account['others']) && $checkbox_data_account['others'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                            <label for="checkbox_others">Others</label><br>

                                                            <input type="text" name="others_text_acc" id="others_text_acc" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_data_account['others_description_acc'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     

                                            <hr class="divider"> 
                                            
                                            <div class="design-text">
                                               Complete Details
                                            </div>

                                            <hr class="divider"> 
                                            
                                            <div class="col-md-12">
                                                <div class="form-group position-relative">
                                                    <label>Indicate all the details needed</label>
                                                    <div class="textarea-wrapper">
                                                        <textarea class="form-control" name="complete_details" id="complete_details" placeholder="" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?=$readonly?>><?= $trf['complete_details']; ?></textarea>
                                                        <span class="placeholder-text">Please attach file if needed</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- New Section for File Display -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>View File</label><br>
                                                    <?php if (!empty($trf['file'])): ?>
                                                        <a href="<?= site_url('uploads/tracc_request/' . $trf['file']); ?>" target="_blank" class="btn btn-primary">
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
                                                    <select class="form-control select2" name="priority" id="priority" disabled>
                                                        <option value=""disabled selected>Priority</option>
                                                        <option value="Low"<?php if ($trf['priority'] == 'Low') echo ' selected'; ?>>Low</option>
                                                        <option value="Medium"<?php if ($trf['priority'] == 'Medium') echo ' selected'; ?>>Medium</option>
                                                        <option value="High"<?php if ($trf['priority'] == 'High') echo ' selected'; ?>>High</option>
                                                    </select>                    
			                    				</div>                                              
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat" disabled>
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($trf['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($trf['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($trf['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Returned"<?php if ($trf['approval_status'] == 'Returned') echo ' selected'; ?>>Returned</option>
                                                    </select>       
                                                </div>
                                            </div>
            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ICT Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" disabled>
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($trf['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($trf['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($trf['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($trf['it_approval_status'] == 'Resolved') echo ' selected'; ?>>Resolved</option>
                                                        <option value="Closed"<?php if ($trf['it_approval_status'] == 'Closed') echo ' selected'; ?>>Closed</option>

                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 40px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" <?= $disabled?>><?= isset($trf['reason_reject_ticket']) ? htmlspecialchars($trf['reason_reject_ticket']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="returnedReason">
                                                <div class="form-group">
                                                    <label>Reason for Returned Ticket</label>
                                                    <textarea class="form-control" id="returnedReason" name="returnedReason" placeholder="Place the reason here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly><?= isset($trf['returned_ticket_reason']) ? htmlspecialchars($trf['returned_ticket_reason']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Accomplished by<span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="accomplished_by" id="accomplished_by" disabled>
                                                        <option value=""disabled selected>Accomplished by</option>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $trf['accomplished_by'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                                
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Accomplished by Date <span style = "color:red;">*</span></label>
                                                    <input type="date" name="accomplished_by_date" id="accomplished_by_date" class="form-control select2" value="<?= $trf['accomplished_by_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By</label>
                                                    <input type="text" name="acknowledge_by" id="acknowledge_by" class="form-control" value="<?= $trf['acknowledge_by']; ?>" style="width: 100%;" required <?=$open_disabled?>>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By/Date</label>
                                                    <input type="date" name="acknowledge_by_date" id="acknowledge_by_date" class="form-control select2" value="<?= $trf['acknowledge_by_date']; ?>" style="width: 100%;" required <?=$open_disabled?>>
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="action" value="edit">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button type="submit" class="btn btn-primary" name="edit" <?=$disabled?>><?=$btn_label?></button>
                                                        <button type="submit" class="btn btn-success" name="acknowledge" onclick="document.querySelector('[name=action]').value='acknowledge';" <?= ($status_trf === 'Open' || $status_trf === 'Rejected' || $status_trf === 'Approved' || $status_trf === 'Returned' || $status_trf === 'Closed') ? 'disabled' : '' ?>>Acknowledge as Resolved</button>
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
        // Apply the resize function to the textarea on input
        $('#complete_details').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#complete_details').each(autoResizeTextarea);

        $("#reason_rejected_ticket").hide();
        $("#returnedReason").hide();

        let date_req = new Date("<?= $trf['date_requested'] ?>");
        let date_req_date = date_req.getDate().toString().padStart(2, '0');
        let date_req_month = (date_req.getMonth() + 1).toString().padStart(2, '0');
        $('#date_requested').val(`${date_req_month}/${date_req_date}/${date_req.getFullYear()}`);
        

        checkApprovalStatus();
        checkReturnTicket();

        // Initially toggle visibility based on checkbox states
        toggleInputField('#checkbox_others_newadd', '#others_text_newadd');
        toggleInputField('#checkbox_others_update', '#others_text_update');
        toggleInputField('#checkbox_others', '#others_text_acc');

        // Event listeners for each checkbox
        $('#checkbox_others_newadd').change(function () {
            toggleInputField(this, '#others_text_newadd');
        });

        $('#checkbox_others_update').change(function () {
            toggleInputField(this, '#others_text_update');
        });

        $('#checkbox_others').change(function () {
            toggleInputField(this, '#others_text_acc');
        });

    });

    function autoResizeTextarea() {
        $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
        $(this).height(this.scrollHeight); // Set height based on content
    }

    function resizeInput(input) {
        input.style.width = 'auto';
        input.style.width = (input.value.length + 1) + 'ch'; // Adjusting based on character length
    }

    // Function to toggle visibility of related input fields
    function toggleInputField(checkbox, inputField) {
        if ($(checkbox).is(':checked')) {
            $(inputField).show();
        } else {
            $(inputField).hide();
        }
    }

    function setAcknowledge() {
        // Get the acknowledge fields
        var ackBy = document.getElementById('acknowledge_by');
        var ackByDate = document.getElementById('acknowledge_by_date');

        // Ensure both fields are required before submission
        ackBy.setAttribute('required', 'required');
        ackByDate.setAttribute('required', 'required');
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