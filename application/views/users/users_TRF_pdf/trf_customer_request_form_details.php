<?php 
    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];

    $reqForm['approved_by'];
    // print_r($reqForm['approved_by']);
    // die();
   
    $disabled = "";
    $readonly = "";
    $btn_label = "Update Ticket";
    
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
                Customer Request Form TMS Details
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-users"></i> Home</a></li>
                <li class="active">Customer Request Form</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#request_form" data-toggle="tab">Customer Request Form Tickets</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="request_form">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('users/details/concern/customer_req/update/' . $reqForm['recid']); ?>" method="POST">
                                            <div class="col-md-12">
                                                <input type="text" name="trf_number" id="trf_number" class="form-control" value="<?= $reqForm['ticket_id']; ?>" readonly>
                                            </div>
                                            <div class="row">
                                                <!-- Checkboxes Section -->
                                                <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                    <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <?php
                                                            $availableCompanies = ['LMI', 'RGDI', 'LPI', 'SV'];
                                                            echo '<script>';
                                                            echo 'console.log("Available Companies: ", ' . json_encode($availableCompanies) . ');'; 
                                                            echo '</script>'
                                                            ?>
                                                            <?php foreach ($availableCompanies as $company): ?>
                                                                <div class="checkbox-inline custom-checkbox">
                                                                    <input type="checkbox" name="crf_comp_checkbox_value[]" value="<?= $company; ?>" id="checkbox_<?= strtolower($company); ?>" <?= in_array($company, $selected_companies) ? 'checked' : ''; ?> <?= $disabled ?>>
                                                                    <label for="checkbox_<?= $company; ?>" class="checkbox-label"><?= $company; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Date Input Section (Label beside the date input) -->
                                                <div class="col-md-3" style="margin-top: 15px;">
                                                    <div class="form-group d-flex align-items-center custom-form-group">
                                                        <label for="date" class="mr-2">Date</label>
                                                        <input type="date" name="date" id="date" class="form-control" value="<?= $reqForm['date']; ?>" required <?=$disabled?>>
                                                    </div>
                                                </div>
                                            </div>          

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Customer Code</label>
                                                    <input type="text" name="customer_code" id="customer_code" value="<?= $reqForm['customer_code']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>TIN No</label>
                                                    <input type="text" name="tin_no" id="tin_no" value="<?= $reqForm['tin_no']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Customer Name</label>
                                                    <input type="text" name="customer_name" id="customer_name" value="<?= $reqForm['customer_name']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Terms</label>
                                                    <input type="text" name="terms" id="terms" value="<?= $reqForm['terms']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Customer Address</label>
                                                    <input type="text" name="customer_address" id="customer_address" value="<?= $reqForm['customer_address']; ?>" class="form-control select2" required <?=$readonly?>>
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
                                                    <input type="text" name="contact_person" id="contact_person" value="<?= $reqForm['contact_person']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Price List</label>
                                                    <input type="text" name="pricelist" id="pricelist" value="<?= $reqForm['pricelist']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office Tel. No.</label>
                                                    <input type="text" name="office_tel_no" id="office_tel_no" value="<?= $reqForm['office_tel_no']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Payment Group</label>
                                                    <input type="text" name="payment_grp" id="payment_grp" value="<?= $reqForm['payment_group']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>            
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact No.</label>
                                                    <input type="text" name="contact_no" id="contact_no" value="<?= $reqForm['contact_no']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Territory</label>
                                                    <input type="text" name="territory" id="territory" value="<?= $reqForm['territory']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Salesman</label>
                                                    <input type="text" name="salesman" id="salesman" value="<?= $reqForm['salesman']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Business Style</label>
                                                    <input type="text" name="business_style" id="business_style" value="<?= $reqForm['business_style']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" id="email" value="<?= $reqForm['email']; ?>" class="form-control select2" required <?=$readonly?>> 
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
                                                                <input class="form-check-input" type="checkbox" id="checkbox_outright" name="checkbox_outright" value="1" <?= $del_days['outright'] == 1 ? 'checked' : '' ?> <?=$disabled?>>
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start" style="font-weight: bold">Online</td>
                                                            <td colspan="2" class="text-center">
                                                                <input class="form-check-input" type="checkbox" id="checkbox_online" name="checkbox_online" value="1" <?= $del_days['online'] == 1 ? 'checked' : '' ?> <?=$disabled?>>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start" style="font-weight: bold">Consignment</td>
                                                            <td colspan="2" class="text-center">
                                                                <input class="form-check-input" type="checkbox" id="checkbox_consignment" name="checkbox_consignment" value="1" <?= $del_days['consignment'] == 1 ? 'checked' : '' ?> <?=$disabled?>>
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start" style="font-weight: bold">Walk-In</td>
                                                            <td colspan="2" class="text-center">
                                                                <input class="form-check-input" type="checkbox" id="checkbox_walkIn" name="checkbox_walkIn" value="1" <?= $del_days['walk_in'] == 1 ? 'checked' : '' ?> <?=$disabled?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="text-start" style="font-weight: bold">Customer is Also a Supplier</td>
                                                            <td colspan="2" class="text-center">
                                                                <input class="form-check-input" type="checkbox" id="checkbox_cus_a_supplier" name="checkbox_cus_a_supplier" value="1" <?= $del_days['customer_is_also_a_supplier'] == 1 ? 'checked' : '' ?> <?=$disabled?>>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check me-3">  
                                                        <input class="form-check-input" type="checkbox" id="checkbox_outright" name="checkbox_outright" value="1" <?= $del_days['outright'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="checkbox_outright">Outright</label>
                                                    </div>
                                                    <div class="form-check me-3">  
                                                        <input class="form-check-input" type="checkbox" id="checkbox_consignment" name="checkbox_consignment" value="1" <?= $del_days['consignment'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="checkbox_consignment">Consignment</label>
                                                    </div>
                                                    <div class="form-check me-3">  
                                                        <input class="form-check-input" type="checkbox" id="checkbox_cus_a_supplier" name="checkbox_cus_a_supplier" value="1" <?= $del_days['customer_is_also_a_supplier'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="checkbox_cus_a_supplier">Customer is also a Supplier</label>
                                                    </div>
                                                    <div class="form-check me-3">  
                                                        <input class="form-check-input" type="checkbox" id="checkbox_online" name="checkbox_online" value="1" <?= $del_days['online'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="checkbox_online">ONLINE</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkbox_walkIn" name="checkbox_walkIn" value="1" <?= $del_days['walk_in'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="checkbox_walkIn">WALK-IN</label>
                                                    </div>
                                                </div>
                                            </div> -->


                                            <hr class="divider"> 

                                            <div class="design-text text-center">
                                                <b>Customer Shipping Setup</b>
                                            </div>

                                            <hr class="divider"> 

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping Code</label>
                                                    <input type="text" name="shipping_code" id="shipping_code" value="<?= $reqForm['shipping_code']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Route Code</label>
                                                    <input type="text" name="route_code" id="route_code" value="<?= $reqForm['route_code']; ?>" class="form-control select2" required <?=$readonly?>> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Customer Shipping Address</label>
                                                    <input type="text" name="customer_shipping_address" id="customer_shipping_address" value="<?= $reqForm['customer_shipping_address']; ?>" class="form-control select2" required <?=$readonly?>>
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
                                                    <input type="text" name="landmark" id="landmark" value="<?= $reqForm['landmark']; ?>" class="form-control select2" <?=$readonly?>>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Window Time Start</label>
                                                    <input type="time" name="window_time_start" id="window_time_start" value="<?= $reqForm['window_time_start']; ?>" class="form-control select2" required <?=$readonly?>>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Window Time End</label>
                                                    <input type="time" name="window_time_end" id="window_time_end" value="<?= $reqForm['window_time_end']; ?>" class="form-control select2" required <?=$readonly?>>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Special Instruction</label>
                                                    <input type="text" name="special_instruction" id="special_instruction" value="<?= $reqForm['special_instruction']; ?>" class="form-control select2" <?=$readonly?>>
                                                </div>
                                            </div>


                                            <div class="col-md-12 text-center"> 
                                                <label style="font-size:20px;">Delivery Days</label> 
                                                <div class="d-flex flex-wrap"> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_monday" name="checkbox_monday" value="1" <?= $del_days['monday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                        <label class="form-check-label" for="checkbox_monday">Monday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_tuesday" name="checkbox_tuesday" value="1" <?= $del_days['tuesday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                        <label class="form-check-label" for="checkbox_tuesday">Tuesday</label> 
                                                    </div> <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_wednesday" name="checkbox_wednesday" value="1" <?= $del_days['wednesday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label class="form-check-label" for="checkbox_wednesday">Wednesday</label> 
                                                    </div> <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_thursday" name="checkbox_thursday" value="1" <?= $del_days['thursday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                        <label class="form-check-label" for="checkbox_thursday">Thursday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_friday" name="checkbox_friday" value="1" <?= $del_days['friday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>> 
                                                        <label class="form-check-label" for="checkbox_friday">Friday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_saturday" name="checkbox_saturday" value="1" <?= $del_days['saturday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
                                                        <label class="form-check-label" for="checkbox_saturday">Saturday</label> 
                                                    </div> 
                                                    <div class="form-check form-check-inline-custom"> 
                                                        <input class="form-check-input" type="checkbox" id="checkbox_sunday" name="checkbox_sunday" value="1" <?= $del_days['sunday'] == 1 ? 'checked' : ''; ?> <?=$disabled?>>
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
                                            
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Encoded By</label>
                                                    <input type="text" name="encoded_by" id="encoded_by" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approved By</label>
                                                    <input type="text" name="approved_by" id="approved_by" value="" class="form-control select2"> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Checked By</label>
                                                    <input type="text" name="checked_by" id="checked_by" value="" class="form-control select2"> 
                                                </div>
                                            </div> -->
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?=$disabled?>><?=$btn_label?></button>
                                                        <!-- <button id="add-form-button" type="button" class="btn btn-primary">Create Another Form</button> -->
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