<?php 
    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];

    $reqForm['approved_by'];
    // print_r($reqForm['approved_by']);
    // die();
   
    $disabled = "";
    $readonly = "";
    $btn_label = "Update  Ticket";
    
    $approved_by = isset($reqForm['approved_by']) ? $reqForm['approved_by'] : null;

    if ($role === "L1") {
        if(!empty($approved_by)) {
            $disabled = "disabled";
            $readonly = "readonly";
            $btn_label = "Submit Ticket";
        } else {
            $disabled = "";
            $readonly = "";
        }
    } 
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

    .custom-form-group {
        display: flex;
        align-items: center;
        margin-top: 10px; /* Adjust this value as needed */
    }

    .custom-form-group label {
        margin-right: 10px;
        margin-bottom: 0; /* Ensures no bottom margin */
    }

    .custom-form-group input[type="date"] {
        flex: 1; /* Ensures the input field takes up available space */
    }

    .divider {
        height: 2px; /* Height of the line */
        background-color: #ccc; /* Color of the line */
        margin: 10px 0; /* Space above and below the line */
        width: 99%; /* Adjust width as needed */
        margin-left: auto; /* Center the line */
        margin-right: auto; /* Center the line */
    }

    .form-check-inline-custom { 
        display: inline-block; 
        align-items: center;
        margin-right: 20px; /* Adjust spacing as needed */ 
    }

    .form-check-label { 
        font-size: 1.35em;
        margin-left: 10px; /* Optional: space between checkbox and label */ 
        vertical-align: middle;
        height: 1.25em; 
    }

    .form-check-input { 
        width: 1.25em; 
        height: 1.25em; 
        vertical-align: middle;
    }

</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Customer Shipping Setup Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">TRACC Request Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Customer Shipping Setup</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('sys/users/details/concern/customer_req_shipping_setup/update/' . $reqForm['recid']); ?>" method="POST">
                                            <div class="col-md-12">
                                                <input type="text" value="<?= $reqForm['ticket_id']; ?>" name="trf_number" class="form-control" readonly>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ticket ID</label>
                                                    <input type="text" name="ticket_id" id="ticket_id" value="" class="form-control select2" required> 
                                                </div>
                                            </div> -->

                                            <div class="row">
                                                <!-- Checkboxes Section -->
                                                <div class="col-md-12 text-center" style="margin-top: 15px;">
                                                    <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="css_comp_checkbox_value[]" value="LMI" id="checkbox_lmi" <?= in_array("LMI", $companies) ? 'checked' : '' ?> <?= $disabled; ?>>
                                                            <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="css_comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi" <?= in_array("RGDI", $companies) ? 'checked' : '' ?> <?= $disabled; ?>>
                                                            <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="css_comp_checkbox_value[]" value="LPI" id="checkbox_lpi" <?= in_array("LPI", $companies) ? 'checked' : '' ?> <?= $disabled; ?>>
                                                            <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="css_comp_checkbox_value[]" value="SVI" id="checkbox_sv" <?= in_array("SVI", $companies) ? 'checked' : '' ?> <?= $disabled; ?>>
                                                            <label for="checkbox_sv" class="checkbox-label">SVI</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>          

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping Code</label>
                                                    <input type="text" name="shipping_code" id="shipping_code" value="<?= $reqForm['shipping_code']; ?>" class="form-control select2" <?= $readonly; ?> required> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Route Code</label>
                                                    <input type="text" name="route_code" id="route_code" value="<?= $reqForm['route_code']; ?>" class="form-control select2" <?= $readonly; ?> required> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Customer Address</label>
                                                    <input type="text" name="customer_address" id="customer_address" value="<?= $reqForm['customer_address']; ?>" class="form-control select2" <?= $readonly; ?> required>
                                                    <small class="form-text text-muted">
                                                        <table class="table table-borderless mb-0">
                                                            <tr>
                                                                <td>Building/House No</td>
                                                                <td>Street/Phase</td>
                                                                <td>Barangay</td>
                                                                <td>City/Municipality</td>
                                                                <td>Postal Code</td>
                                                                <td>Province</td>
                                                            </tr>
                                                        </table>
                                                    </small>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Landmark</label>
                                                    <input type="text" name="landmark" id="landmark" value="<?= $reqForm['landmark']; ?>" class="form-control select2" <?= $readonly; ?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Window Time Start</label>
                                                    <input type="time" name="window_time_start" id="window_time_start" value="<?= $reqForm['window_time_start'] ?>" class="form-control select2" <?= $readonly; ?> required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Window Time End</label>
                                                    <input type="time" name="window_time_end" id="window_time_end" value="<?= $reqForm['window_time_end']; ?>" class="form-control select2" <?= $readonly; ?> required>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Special Instruction</label>
                                                    <input type="text" name="special_instruction" id="special_instruction" value="<?= $reqForm['special_instruction']; ?>" class="form-control select2" <?= $readonly; ?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center"> 
                                                <label style="font-size:20px;">Delivery Days</label> 
                                                <div class="d-flex flex-wrap"> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_monday" name="checkbox_monday" value="1" <?= $reqForm['monday'] ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                        <label class="form-check-label" for="checkbox_monday">Monday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_tuesday" name="checkbox_tuesday" value="1" <?= $reqForm['tuesday'] ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                        <label class="form-check-label" for="checkbox_tuesday">Tuesday</label> 
                                                    </div> <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_wednesday" name="checkbox_wednesday" value="1" <?= $reqForm['wednesday'] ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                        <label class="form-check-label" for="checkbox_wednesday">Wednesday</label> 
                                                    </div> <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_thursday" name="checkbox_thursday" value="1" <?= $reqForm['thursday'] ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                        <label class="form-check-label" for="checkbox_thursday">Thursday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_friday" name="checkbox_friday" value="1" <?= $reqForm['friday'] ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                        <label class="form-check-label" for="checkbox_friday">Friday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_saturday" name="checkbox_saturday" value="1" <?= $reqForm['saturday'] ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                        <label class="form-check-label" for="checkbox_saturday">Saturday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_sunday" name="checkbox_sunday" value="1" <?= $reqForm['sunday'] ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                        <label class="form-check-label" for="checkbox_sunday">Sunday</label>
                                                    </div> 
                                                </div> 
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Requested By</label>
                                                    <input type="text" name="requested_by" id="requested_by" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" required readonly> 
                                                </div>
                                            </div>
                                                                    
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?= $disabled; ?>>Submit Tickets</button>
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
