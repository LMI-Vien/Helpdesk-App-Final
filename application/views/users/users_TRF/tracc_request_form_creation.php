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
			                <li class="active" style="background-color: yellow;"><a href="#msrf" data-toggle="tab">Ticket for TRACC Request</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form id="TRF_form" action="<?= site_url('UsersTraccReq_controller/user_creation_tickets_tracc_request'); ?>" method="POST" enctype="multipart/form-data">
                                            <div class="col-md-12">
			                    				<div class="form-group">
                                                    <!-- -->
			                    					<label>TRF#</label>
                                                    <input type="text" name="trf_number" id="trf_number" class="form-control" value="<?php echo htmlspecialchars($trf); ?>" readonly required>
			                    				</div>                                               
			                    			</div>
                                            <!-- -->
                                            <div class="col-md-6" style="display: none;">
                                                <div class="form-group">
                                                    <label>Requested by</label>
                                                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" readonly>
                                                </div>
                                            </div>
                                            <!-- -->
                                            <div class="col-md-6" style="display: none;">
                                                <div class="form-group">
                                                    <label>Date Requested</label>
                                                    <input type="date" name="date_req" id="date_req" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>
                                            <!-- -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                        <input type="text" name="department_description" id="department_description" value="<?php echo isset($get_department[1]['dept_desc']) ? htmlspecialchars($get_department[1]['dept_desc']) : ''; ?>" class="form-control select2" style="width: 100%;" readonly>
                                                        <input type="hidden" name="dept_id" value="<?php echo $users_det['dept_id']; ?>">
                                                        <!-- If supervisor_id is also needed -->
                                                        <input type="hidden" name="sup_id" value="<?php echo htmlspecialchars($users_det['sup_id']); ?>">
                                                </div>    
                                            </div>   
                                            <!-- -->
                                            <div class="col-md-6">
                                                <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_needed" id="date_needed" class="form-control select2" style="width: 100%;" min="<?= date('Y-m-d'); ?>" required>
			                                    </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <div class="form-group d-flex justify-content-center">
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" value="LMI" id="checkbox_lmi">
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi">
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" value="LPI" id="checkbox_lpi">
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="comp_checkbox_value[]" value="SV" id="checkbox_sv">
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
                                                <div class="col-md-4 text-left">
                                                    <div class="form-group d-flex flex-column align-items-center" style="margin-left: 80px;">
                                                        <label for="" style="font-size: 21px;">New/Add</label>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_item" id="checkbox_item" value="1">
                                                            <label for="checkbox_item">Item</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer" id="checkbox_customer" value="1">
                                                            <label for="checkbox_customer">Customer</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier" id="checkbox_supplier" value="1">
                                                            <label for="checkbox_supplier">Supplier</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_whs" id="checkbox_whs" value="1">
                                                            <label for="checkbox_whs">Warehouse</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_bin" id="checkbox_bin" value="1">
                                                            <label for="checkbox_bin">Bin No.</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_cus_ship_setup" id="checkbox_cus_ship_setup" value="1">
                                                            <label for="checkbox_cus_ship_setup">Customer Shipping Setup</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_employee_req_form" id="checkbox_employee_req_form" value="1">
                                                            <label for="checkbox_employee_req_form">Employee Request Form</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_newadd" id="checkbox_others_newadd" value="1">
                                                            <label for="checkbox_others_newadd">Others</label><br>

                                                            <input type="text" name="others_text_newadd" id="others_text_newadd" placeholder="Specific Concern" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);"> <!-- Line input style with min-width -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-left"">
                                                    <div class="form-group d-flex flex-column align-items-center" style="margin-left: 80px;">
                                                        <label for="" style="font-size: 21px;">Update</label>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_system_date_lock" id="checkbox_system_date_lock" value="1">
                                                            <label for="checkbox_system_date_lock">System Date Lock</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_user_file_access" id="checkbox_user_file_access" value="1">
                                                            <label for="checkbox_user_file_access">User File Access</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_item_dets" id="checkbox_item_dets" value="1">
                                                            <label for="checkbox_item_dets">Item Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_customer_dets" id="checkbox_customer_dets" value="1">
                                                            <label for="checkbox_customer_dets">Customer Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_supplier_dets" id="checkbox_supplier_dets" value="1">
                                                            <label for="checkbox_supplier_dets">Supplier Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_employee_dets" id="checkbox_employee_dets" value="1">
                                                            <label for="checkbox_employee_dets">Employee Details</label>
                                                        </div>
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_update" id="checkbox_others_update" value="1">
                                                            <label for="checkbox_others_update">Others</label><br>
                                                            
                                                            <input type="text" name="others_text_update" id="others_text_update" placeholder="Specific Concern" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-left">
                                                    <div class="form-group d-flex flex-column align-items-start" style="margin-left: 80px;">
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_tracc_orien" id="checkbox_tracc_orien" value="1">
                                                            <label for="checkbox_tracc_orien">Tracc Orientation</label>
                                                        </div>
                                                        <label for="" style="font-size: 21px;">Create TRACC Account</label>
                                                        
                                                        <!-- Use CSS Grid for precise alignment -->
                                                        <div class="d-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; width: 100%;">
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lmi" id="checkbox_create_lmi" value="1">
                                                                <label for="checkbox_create_lmi">LMI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_lpi" id="checkbox_create_lpi" value="1">
                                                                <label for="checkbox_create_lpi">LPI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_rgdi" id="checkbox_create_rgdi" value="1">
                                                                <label for="checkbox_create_rgdi">RGDI</label>
                                                            </div>
                                                            <div class="circle-checkbox mb-2">
                                                                <input type="checkbox" name="checkbox_create_sv" id="checkbox_create_sv" value="1">
                                                                <label for="checkbox_create_sv">SV</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2">
                                                            <input type="checkbox" name="checkbox_gps_account" id="checkbox_gps_account" value="1">
                                                            <label for="checkbox_gps_account">GPS Account</label>
                                                        </div>
                                                        
                                                        <div class="circle-checkbox mb-2 d-flex align-items-center">
                                                            <input type="checkbox" name="checkbox_others_account" id="checkbox_others_account" value="1">
                                                            <label for="checkbox_others_account">Others</label><br>

                                                            <input type="text" name="others_text_account" id="others_text_account" placeholder="Specific Concern" style="border: none; border-bottom: 1px solid #000; background: none; min-width: 150px;" oninput="resizeInput(this);">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     

                                            <hr class="divider"> 
                                            
                                            <div class="design-text">
                                               Complete Details
                                            </div>

                                            <hr class="divider"> 
                                            <!-- -->
                                            <div class="col-md-12">
                                                <div class="form-group position-relative">
                                                    <label>Indicate all the details needed</label>
                                                    <div class="textarea-wrapper">
                                                        <textarea class="form-control" name="complete_details" id="complete_details" placeholder=" " style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" required></textarea>
                                                        <span class="placeholder-text">Please attach file if needed</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <input type="file" name="uploaded_files" id="uploaded_files" class="form-control" accept="image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By</label>
                                                    <input type="text" name="acknowledge_by" id="acknowledge_by" class="form-control" value="" style="width: 100%;" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge By/Date</label>
                                                    <input type="date" name="acknowledge_by_date" id="acknowledge_by_date" class="form-control select2" value="" style="width: 100%;" required>
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
        $('#date_req').val(new Date().toISOString().split('T')[0]);

        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        $('#complete_details').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#complete_details').each(autoResizeTextarea);

        function resizeInput(input) {
            input.style.width = 'auto';
            input.style.width = (input.value.length + 1) + 'ch'; // Adjusting based on character length
        }

        function toggleText(checkbox, textField) {
            if ($(checkbox).prop('checked') === false) {
                $(textField).hide();
            }

            $(checkbox).change(function() {
                if (this.checked) {
                    $(textField).show();
                } else {
                    $(textField).hide();
                }
            });
        }
        
        // Apply to both checkbox/input sets
        toggleText('#checkbox_others_newadd', '#others_text_newadd');
        toggleText('#checkbox_others_update', '#others_text_update');
        toggleText('#checkbox_others_account', '#others_text_account');

        $('#submitBtn').click(function (e) {
            e.preventDefault();

            var form = document.getElementById('TRF_form');
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
                    $('#TRF_form').submit(); 
                }
            });
        });
    });

</script>