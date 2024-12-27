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
				TRACC Request Creation
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
                                        <form action="<?= site_url('AdminTraccReq_controller/admin_list_tracc_request'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>TRF#</label>
                                                    <input type="text" name="trf_number" id="trf_number" class="form-control" value="<?= $trf['ticket_id']; ?>" readonly>
			                    				</div>                                               
			                    			</div>

                                            <div class="col-md-6" style="display: none;">
                                                <div class="form-group">
                                                    <label>Requested_by</label>
                                                    <input type="text" name="name" value="<?= $trf['requested_by'] ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6" style="display: none;">
                                                <div class="form-group">
                                                    <label>Date Requested</label>
                                                    <input type="date" name="date_requested" id="date_requested" class="form-control select2" value="<?= $trf['date_requested'] ?>" style="width: 100%;" readonly>
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
			                                        <input type="date" name="date_need" id="date_need" value="<?= $trf['date_needed']; ?>" class="form-control select2" style="width: 100%;" readonly>
			                                    </div>
                                            </div>

                                            <!-- <?= var_dump($company); ?> -->

                                            <div class="col-md-12 text-center">
                                                <div class="form-group d-flex justify-content-center">
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="checkbox_lmi" id="checkbox_lmi" value="LMI" <?= in_array("LMI", $company) ? 'checked' : '' ?> disabled readonly>
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="checkbox_rgdi" id="checkbox_rgdi" value="RGDI" <?= in_array("RGDI", $company) ? 'checked' : '' ?> disabled readonly>
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="checkbox_lpi" id="checkbox_lpi" value="LPI" <?= in_array("LPI", $company) ? 'checked' : '' ?> disabled readonly>
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="checkbox_sv" id="checkbox_sv" value="SV" <?= in_array("SV", $company) ? 'checked' : '' ?> disabled readonly>
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
                                                            <input type="checkbox" name="checkbox_item" id="checkbox_item" value=1
                                                            <?= isset($checkbox_newadd['item']) && $checkbox_newadd['item'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_item">Item</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer" id="checkbox_customer" value=1
                                                            <?= isset($checkbox_newadd['customer']) && $checkbox_newadd['customer'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_customer">Customer</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier" id="checkbox_supplier" value=1
                                                            <?= isset($checkbox_newadd['supplier']) && $checkbox_newadd['supplier'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_supplier">Supplier</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_whs" id="checkbox_whs" value=1
                                                            <?= isset($checkbox_newadd['warehouse']) && $checkbox_newadd['warehouse'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_whs">Warehouse</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_bin" id="checkbox_bin" value=1
                                                            <?= isset($checkbox_newadd['bin_number']) && $checkbox_newadd['bin_number'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_bin">Bin No.</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_cus_ship_setup" id="checkbox_cus_ship_setup" value=1
                                                            <?= isset($checkbox_newadd['customer_shipping_setup']) && $checkbox_newadd['customer_shipping_setup'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_cus_ship_setup">Customer Shipping Setup</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="employee_request_form" id="employee_request_form" value=1
                                                            <?= isset($checkbox_newadd['employee_request_form']) && $checkbox_newadd['employee_request_form'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="employee_request_form">Employee Request Form</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_newadd" id="checkbox_others_newadd" value=1
                                                            <?= isset($checkbox_newadd['others']) && $checkbox_newadd['others'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_others_newadd">Others</label><br>

                                                            <input type="text" name="others_text" id="others_text" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_newadd['others_description_add'] ?>" disabled readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 text-left" style="margin-left: -150px;">
                                                    <div class="form-group d-flex flex-column align-items-center">
                                                        <label for="" style="font-size: 21px;">Update</label>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_system_date_lock" id="checkbox_system_date_lock" value=1 
                                                            <?= isset($checkbox_update['system_date_lock']) && $checkbox_update['system_date_lock'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_system_date_lock">System Date Lock</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_user_file_access" id="checkbox_user_file_access" value=1
                                                            <?= isset($checkbox_update['user_file_access']) && $checkbox_update['user_file_access'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_user_file_access">User File Access</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_item_dets" id="checkbox_item_dets" value=1
                                                            <?= isset($checkbox_update['item_details']) && $checkbox_update['item_details'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_item_dets">Item Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer_dets" id="checkbox_customer_dets" value=1
                                                            <?= isset($checkbox_update['customer_details']) && $checkbox_update['customer_details'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_customer_dets">Customer Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier_dets" id="checkbox_supplier_dets" value=1
                                                            <?= isset($checkbox_update['supplier_details']) && $checkbox_update['supplier_details'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_supplier_dets">Supplier Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier_dets" id="checkbox_supplier_dets" value=1
                                                            <?= isset($checkbox_update['employee_details']) && $checkbox_update['employee_details'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_supplier_dets">Employee Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_update" id="checkbox_others_update" value=1
                                                            <?= isset($checkbox_update['others']) && $checkbox_update['others'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_others_update">Others</label><br>

                                                            <input type="text" name="others_text" id="others_text" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_update['others_description_update'] ?>" disabled readonly>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-4 text-left" style="margin-left: -250px;">
                                                    <div class="form-group d-flex flex-column align-items-start">
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_tracc_orien" id="checkbox_tracc_orien" value=1
                                                            <?= isset($checkbox_account['tracc_orientation']) && $checkbox_account['tracc_orientation'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_tracc_orien">Tracc Orientation</label>
                                                        </div>
                                                        <label for="" style="font-size: 21px;">Create TRACC Account</label>
                                                        
                                                        <!-- Use CSS Grid for precise alignment -->
                                                        <div class="d-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; width: 100%;">
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lmi" id="checkbox_create_lmi" value=1
                                                                <?= isset($checkbox_account['lmi']) && $checkbox_account['lmi'] ? 'checked' : ''; ?> disabled readonly>
                                                                <label for="checkbox_create_lmi">LMI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lpi" id="checkbox_create_lpi" value=1
                                                                <?= isset($checkbox_account['lpi']) && $checkbox_account['lpi'] ? 'checked' : ''; ?> disabled readonly>
                                                                <label for="checkbox_create_lpi">LPI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_rgdi" id="checkbox_create_rgdi" value=1
                                                                <?= isset($checkbox_account['rgdi']) && $checkbox_account['rgdi'] ? 'checked' : ''; ?> disabled readonly>
                                                                <label for="checkbox_create_rgdi">RGDI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_sv" id="checkbox_create_sv" value=1
                                                                <?= isset($checkbox_account['sv']) && $checkbox_account['sv'] ? 'checked' : ''; ?> disabled readonly>
                                                                <label for="checkbox_create_sv">SV</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_gps_account" id="checkbox_gps_account" value=1
                                                            <?= isset($checkbox_account['others']) && $checkbox_account['others'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_gps_account">GPS Account</label>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others" id="checkbox_others" value=1
                                                            <?= isset($checkbox_account['others']) && $checkbox_account['others'] ? 'checked' : ''; ?> disabled readonly>
                                                            <label for="checkbox_others">Others</label><br>

                                                            <input type="text" name="others_text" id="others_text" placeholder="" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);" value="<?= $checkbox_account['others_description_acc'] ?>" readonly>
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
                                                        <textarea class="form-control" name="complete_details" id="complete_details" placeholder="" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" disabled><?= $trf['complete_details']; ?></textarea>
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

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By</label>
                                                    <input type="text" name="acknowledge_by" id="acknowledge_by" class="form-control" value="<?= $trf['acknowledge_by']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By/Date</label>
                                                    <input type="date" name="acknowledge_by_date" id="acknowledge_by_date" class="form-control select2" value="<?= $trf['acknowledge_by_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
			                    					<label>Priority</label>
                                                    <select class="form-control select2" name="priority" id="priority" disabled readonly>
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
                                                    <select class="form-control select2" name="app_stat" id="app_stat" disabled readonly>
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
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" disabled readonly>
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($trf['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($trf['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($trf['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($trf['it_approval_status'] == 'Resolved') echo ' selected'; ?>>Resolved</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="reason_rejected_ticket">
                                                <div class="form-group">
                                                    <label>Reason for Rejected Ticket</label>
                                                    <textarea class="form-control" id="reason_rejected" name="reason_rejected" placeholder="Place the reason here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" disabled readonly><?= isset($trf['reason_reject_tickets']) ? htmlspecialchars($trf['reason_reject_tickets']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Accomplished by<span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="accomplished_by" id="accomplished_by" disabled readonly>
                                                        <option value=""disabled selected>Accomplished by</option>
                                                        <option value="HANNA" <?php if ($trf['accomplished_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($trf['accomplished_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                        <option value="CK" <?php if ($trf['accomplished_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($trf['accomplished_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Accomplished by Date <span style = "color:red;">*</span></label>
                                                    <input type="date" name="accomplished_by_date" id="accomplished_by_date" class="form-control select2" value="<?= $trf['accomplished_by_date']; ?>" style="width: 100%;" disabled readonly>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="acknowledge_by" id="acknowledge_by" <?= $is_disabled?>>
                                                        <option value=""disabled selected>Acknowledge By</option>
                                                        <option value="HANNA" <?php if ($trf['acknowledge_by'] == 'HANNA') echo ' selected'; ?>>Ms. Hanna</option>
                                                        <option value="DAN" <?php if ($trf['acknowledge_by'] == 'DAN') echo ' selected'; ?>>Sir. Dan</option>
                                                        <option value="CK" <?php if ($trf['acknowledge_by'] == 'CK') echo ' selected'; ?>>Sir. CK</option>
                                                        <option value="ERIC" <?php if ($trf['acknowledge_by'] == 'ERIC') echo ' selected'; ?>>Sir. Eric</option>
                                                        
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge by Date <span style = "color:red;">*</span></label>
                                                    <input type="date" name="ack_by_date" id="ack_by_date" class="form-control select2" value="<?= $trf['acknowledge_by_date']; ?>" style="width: 100%;" <?= $is_disabled?>>
                                                </div>
                                            </div> -->
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
        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        $('#complete_details').on('input', autoResizeTextarea);
        $('#reason_rejected').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#complete_details').each(autoResizeTextarea);
        $('#reason_rejected').on('input', autoResizeTextarea);
        
    });

    function resizeInput(input) {
        input.style.width = 'auto';
        input.style.width = (input.value.length + 1) + 'ch'; // Adjusting based on character length
    }

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

</script>