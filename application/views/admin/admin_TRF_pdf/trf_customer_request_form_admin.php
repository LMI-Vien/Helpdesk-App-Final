<?php 
$role = $this->session->userdata('login_data')['role'];

$disabled = "disabled";
$uneditable = "disabled";

if($user_id) {
    $disabled = "";
}

if($role == "L3" && $remarks != "Done") {
	$uneditable = "";
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

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="msrf">
                        <section id="new">
                            <div class="row">
                                <?php if($role == "L2" && $user_id): ?>
                                    <form method="POST" action="<?= site_url('AdminTraccReq_controller/admin_update_customer_request_form_pdf/' . $recid); ?>">
                                <?php else: ?>
                                    <form action="<?= site_url('AdminTraccReq_controller/approve_crf'); ?>" method="POST">
                                <?php endif; ?>
                                    <input type="hidden" name="recid" id="recid" value="<?php echo $recid; ?>">
                                    <input type="hidden" name="trf_number" value="<?= $ticket_id; ?>">
                                    <input type="hidden" name="approved" value="<?= $user_details[1]['fname'] . ' ' . $user_details[1]['lname']; ?>">
                                    <div class="row">
                                        <div class="col-md-7 text-center" style="margin-top: 15px;">
                                            <div class="form-group d-flex justify-content-center">
                                                <div class="checkbox-inline custom-checkbox">
                                                    <!-- <p style="font-size: 1.7em;">Company Selected: <span style="font-weight: bold;"><?php echo implode(', ', $companies); ?></span></p> -->
                                                    <?php 
                                                    $availableCompanies = ['LMI', 'RGDI', 'LPI', 'SV'];
                                                    echo '<script>';
                                                    echo 'console.log("Available Companies:", ' . json_encode($availableCompanies) . ');';
                                                    echo 'console.log("Checked Companies:", ' . json_encode($companies) . ' );';
                                                    echo '</script>';
                                                    ?>
                                                    <?php foreach ($availableCompanies as $company): ?>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="trf_comp_checkbox_value[]" value="<?= $company; ?>" id="checkbox_<?php echo ($company); ?>"<?php echo in_array($company, $companies) ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                            <label for="" class="checkbox-label"><?php echo $company; ?></label>
                                                        </div>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Date Input Section (Label beside the date input) -->
                                        <div class="col-md-3" style="margin-top: 15px;">
                                            <div class="form-group d-flex align-items-center custom-form-group">
                                                <label for="date" class="mr-2">Date</label>
                                                <input type="date" name="date" id="date" value="<?php echo $date; ?>" class="form-control" required <?= $disabled; ?>>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Code</label>
                                            <input type="text" name="customer_code" id="customer_code" value="<?php echo $customer_code; ?>" class="form-control select2" required <?= $uneditable; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TIN No</label>
                                            <input type="text" name="tin_no" id="tin_no" value="<?php echo $tin_no; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input type="text" name="customer_name" id="customer_name" value="<?php echo $customer_name; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Terms</label>
                                            <input type="text" name="terms" id="terms" value="<?php echo $terms; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Address</label>
                                            <input type="text" name="customer_address" id="customer_address" value="<?php echo $customer_address; ?>" class="form-control select2" required <?= $disabled; ?>>
                                            <small class="form-text text-muted">
                                                <table class="table table-borderless mb-0">
                                                    <tr>
                                                        <td>Building/House No</td>
                                                        <td>Street/Phase</td>
                                                        <td>Barangay</td>
                                                        <td>City/Municipality</td>
                                                        <td>Postal Code</td>
                                                        <td>Province</td>
                                                        <td>Region</td>
                                                        <td>Country</td>
                                                    </tr>
                                                </table>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Person</label>
                                            <input type="text" name="contact_person" id="contact_person" value="<?php echo $contact_person; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price List</label>
                                            <input type="text" name="pricelist" id="pricelist" value="<?php echo $pricelist; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Office Tel. No.</label>
                                            <input type="text" name="office_tel_no" id="office_tel_no" value="<?php echo $office_tel_no; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Payment Group</label>
                                            <input type="text" name="payment_grp" id="payment_grp" value="<?php echo $payment_group; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>            
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact No.</label>
                                            <input type="text" name="contact_no" id="contact_no" value="<?php echo $contact_no; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Territory</label>
                                            <input type="text" name="territory" id="territory" value="<?php echo $territory; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Salesman</label>
                                            <input type="text" name="salesman" id="salesman" value="<?php echo $salesman; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Business Style</label>
                                            <input type="text" name="business_style" id="business_style" value="<?php echo $business_style; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td colspan="2" class="text-center" style="width: 100px"></td>
                                                    <td colspan="2" class="text-center" style="width: 100px"></td>
                                                    <td colspan="2" class="text-center" style="width: 100px"></td>
                                                    <td colspan="2" class="text-center" style="width: 100px"></td>
                                                    <td colspan="2" class="text-center" style="width: 100px"></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" class="text-start" style="font-weight: bold">Outright</td>
                                                    <td colspan="2" class="text-center">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_outright" name="checkbox_outright" value="1"
                                                        <?= isset($checkbox_data['outright']) && $checkbox_data['outright'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                    </td>
                                                    <td colspan="2" class="text-center"></td>
                                                    <td colspan="2" class="text-start" style="font-weight: bold">Online</td>
                                                    <td colspan="2" class="text-center">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_online" name="checkbox_online" value="1"
                                                        <?= isset($checkbox_data['online']) && $checkbox_data['online'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" class="text-start" style="font-weight: bold">Consignment</td>
                                                    <td colspan="2" class="text-center">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_consignment" name="checkbox_consignment" value="1"
                                                        <?= isset($checkbox_data['consignment']) && $checkbox_data['consignment'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                    </td>
                                                    <td colspan="2" class="text-center"></td>
                                                    <td colspan="2" class="text-start" style="font-weight: bold">Walk-In</td>
                                                    <td colspan="2" class="text-center">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_walkIn" name="checkbox_walkIn" value="1"
                                                        <?= isset($checkbox_data['walk_in']) && $checkbox_data['walk_in'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-start" style="font-weight: bold">Customer is Also a Supplier</td>
                                                    <td colspan="2" class="text-center">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_cus_a_supplier" name="checkbox_cus_a_supplier" value="1"
                                                        <?= isset($checkbox_data['customer_is_also_a_supplier']) && $checkbox_data['customer_is_also_a_supplier'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr class="divider"> 

                                    <div class="design-text text-center">
                                        <b>Customer Shipping Setup</b>
                                    </div>

                                    <hr class="divider"> 

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Shipping Code</label>
                                            <input type="text" name="shipping_code" id="shipping_code" value="<?php echo $shipping_code; ?>" class="form-control select2" required <?= $uneditable ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Route Code</label>
                                            <input type="text" name="route_code" id="route_code" value="<?php echo $route_code; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Shipping Address</label>
                                            <input type="text" name="customer_shipping_address" id="customer_shipping_address" value="<?php echo $customer_shipping_address; ?>" class="form-control select2" required <?= $disabled; ?>>
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
                                            <input type="text" name="landmark" id="landmark" value="<?php echo $landmark; ?>" class="form-control select2" required <?= $disabled; ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Window Time Start</label>
                                            <input type="time" name="window_time_start" id="window_time_start" value="<?php echo $window_time_start; ?>" class="form-control select2" required <?= $disabled; ?>>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Window Time End</label>
                                            <input type="time" name="window_time_end" id="window_time_end" value="<?php echo $window_time_end; ?>" class="form-control select2" required <?= $disabled; ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Special Instruction</label>
                                            <input type="text" name="special_instruction" id="special_instruction" value="<?php echo $special_instruction; ?>" class="form-control select2" required <?= $disabled; ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center"> 
                                        <label style="font-size:20px;">Delivery Days</label> 
                                        <div class="d-flex flex-wrap"> 
                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_monday" name="checkbox_monday" value="1"
                                                <?= isset($checkbox_data['monday']) && $checkbox_data['monday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_monday">Monday</label> 
                                            </div> 

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_tuesday" name="checkbox_tuesday" value="1"
                                                <?= isset($checkbox_data['tuesday']) && $checkbox_data['tuesday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_tuesday">Tuesday</label> 
                                            </div>

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_wednesday" name="checkbox_wednesday" value="1"
                                                <?= isset($checkbox_data['wednesday']) && $checkbox_data['wednesday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_wednesday">Wednesday</label> 
                                            </div> 

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_thursday" name="checkbox_thursday" value="1"
                                                <?= isset($checkbox_data['thursday']) && $checkbox_data['thursday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_thursday">Thursday</label> 
                                            </div> 

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_friday" name="checkbox_friday" value="1"
                                                <?= isset($checkbox_data['friday']) && $checkbox_data['friday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_friday">Friday</label> 
                                            </div> 

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_saturday" name="checkbox_saturday" value="1"
                                                <?= isset($checkbox_data['saturday']) && $checkbox_data['saturday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_saturday">Saturday</label> 
                                            </div>

                                            <div class="form-check form-check-inline-custom"> 
                                                <input class="form-check-input" type="checkbox" id="checkbox_sunday" name="checkbox_sunday" value="1"
                                                <?= isset($checkbox_data['sunday']) && $checkbox_data['sunday'] == 1 ? 'checked' : ''; ?> <?= $disabled; ?>> 
                                                <label class="form-check-label" for="checkbox_sunday">Sunday</label> 
                                            </div> 
                                        </div> 
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Requested By</label>
                                            <input type="text" name="requested_by" id="requested_by" value="<?php echo $requested_by; ?>" class="form-control select2" required disabled> 
                                        </div>
                                    </div>

                                    <?php if($role === "L3"): ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Approved By</label>
                                                <input type="text" name="approved_by" id="approved_by" value="<?php echo $approved_by ?>" class="form-control select2" <?php echo ($role === 'L3') ? 'readonly' : ''; ?> required> 
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                    <?php if($role == "L2" && $user_id): ?>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="box-body pad">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else: ?>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="box-body pad">
                                                    <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?php echo ($role === 'L3') ? 'disabled' : ''; ?>>Approved</button>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <p><strong>RECID:</strong> <?php echo $recid; ?></p> 
<p><strong>Ticket ID:</strong> <?php echo $ticket_id; ?></p> 
<p><strong>Requestor Name:</strong> <?php echo $requested_by; ?></p>  -->

