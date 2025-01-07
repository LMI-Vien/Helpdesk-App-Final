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

    /* Specific styling for labels within the supplier group */
    .supplier-group-label {
        margin-right: 50px; /* Add space between label and checkbox */
        display: inline-block; /* Ensure the label is inline with checkbox */
        width: 150px;
    }

    .custom-checkbox-sup input[type="checkbox"] {
        transform: scale(1.5); /* Enlarge checkboxes */
    }

    .checkbox-label-sup {
        font-size: 17px;
    }

    .major-group-label {
        margin-right: 100px; /* Add space between label and checkbox */
        display: inline-block; /* Ensure the label is inline with checkbox */
        width: 250px;
        font-size: 1.2em;
    }

    .custom-checkbox-major-grp input[type="checkbox"] {
        transform: scale(1.5);
    }

    .design-text {
        font-size: 18px; /* Adjust the font size as needed */
        font-weight: bold; /* Optional: makes the text bold */
        color: #333; /* Optional: changes text color */
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
                                <form action="<?= site_url('AdminTraccReq_controller/approve_srf'); ?>" method="POST">
                                <input type="hidden" name="recid" id="recid" value="<?php echo $recid; ?>">
                                <div class="row">
                                    <!-- Checkboxes Section -->
                                    <div class="col-md-7 text-center" style="margin-top: 15px;">
                                        <div class="form-group d-flex justify-content-center">
                                            
                                            <div class="checkbox-inline custom-checkbox">
                                                <!-- <p style="font-size: 1.7em;">Company Selected: <span style="font-weight: bold;"><?php echo implode(', ', $companies); ?></span></p> -->
                                                <?php 
                                                $availableCompanies = ['LMI', 'RGDI', 'LPI', 'SV'];
                                                echo '<script>';
                                                echo 'console.log("Available Companies:", ' . json_encode($availableCompanies) . ');';
                                                echo 'console.log("Checked Companies:", ' . json_encode($companies) . ');';
                                                echo '</script>';
                                                ?>
                                                <?php foreach ($availableCompanies as $company): ?>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="trf_comp_checkbox_value[]" value="" id="checkbox_<?php echo ($company); ?>"<?php echo in_array($company, $companies) ? 'checked' : ''; ?>>
                                                        <label for="checkbox_rgdi" class="checkbox-label"><?php echo $company; ?></label>
                                                    </div>
                                                <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- <?php if (isset($checkbox_data)): ?>
                                        <script>
                                            console.log("Checkbox Data:", <?php echo json_encode($checkbox_data); ?>);
                                            console.log("Checkbox Data for local_supplier_grp:", <?php echo json_encode($checkbox_data['supplier_group_local']); ?>);
                                        </script>
                                    <?php endif; ?> -->
                                    

                                    <div class="col-md-3" style="margin-top: 15px;">
                                        <div class="form-group d-flex align-items-center custom-form-group">
                                            <label for="date" class="mr-2">Date</label>
                                            <input type="date" name="date" id="date" value="<?php echo $date; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>      

                                <hr class="divider">

                                <div class="col-md-12">
                                    <table class="table" style ="border: none">
                                        <thead>
                                            <tr>
                                                <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Supplier Group:</td>
                                                <td colspan="2" class="text-center" style="width: 100px"></td>
                                                <td colspan="2" class="text-center" style="width: 150px"></td>
                                                <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Major Group:</td>
                                                <td colspan="2" class="text-center" style="width: 100px"></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="text-start">01 - Local</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="local_supplier_grp" id="local_supplier_grp" value="1"
                                                    <?= isset($checkbox_data['supplier_group_local']) && $checkbox_data['supplier_group_local'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">001 - Local Trade Vendors</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_local_trade_ven" id="major_grp_local_trade_ven" value="1"
                                                    <?= isset($checkbox_data['major_grp_local_trade_vendor']) && $checkbox_data['major_grp_local_trade_vendor'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">02 - Foreign</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="foreign_supplier_grp" id="foreign_supplier_grp" value="1"
                                                    <?= isset($checkbox_data['supplier_group_foreign']) && $checkbox_data['supplier_group_foreign'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">002 - Local Non-Trade Vendors</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_local_nontrade_ven" id="major_grp_local_nontrade_ven" value="1"
                                                    <?= isset($checkbox_data['major_grp_local_non_trade_vendor']) && $checkbox_data['major_grp_local_non_trade_vendor'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">003 - Foreign Trade Vendors</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_foreign_trade_ven" id="major_grp_foreign_trade_ven" value="1"
                                                    <?= isset($checkbox_data['major_grp_foreign_trade_vendors']) && $checkbox_data['major_grp_foreign_trade_vendors'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Supplier:</td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">004 - Foreign Non-Trade Vendors</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_foreign_nontrade_ven" id="major_grp_foreign_nontrade_ven" value="1"
                                                    <?= isset($checkbox_data['major_grp_foreign_non_trade_vendors']) && $checkbox_data['major_grp_foreign_non_trade_vendors'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">01 - Trade</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="supplier_trade" id="supplier_trade" value="1"
                                                    <?= isset($checkbox_data['supplier_trade']) && $checkbox_data['supplier_trade'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">005 - Local-Broker/Forwarder</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_local_broker_forwarder" id="major_grp_local_broker_forwarder" value="1"
                                                    <?= isset($checkbox_data['major_grp_local_broker_forwarder']) && $checkbox_data['major_grp_local_broker_forwarder'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">02 - Non-Trade</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="supplier_non_trade" id="supplier_non_trade" value="1"
                                                    <?= isset($checkbox_data['supplier_non_trade']) && $checkbox_data['supplier_non_trade'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">006 - Rental</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_rental" id="major_grp_rental" value="1"
                                                    <?= isset($checkbox_data['major_grp_rental']) && $checkbox_data['major_grp_rental'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">007 - Bank</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_bank" id="major_grp_bank" value="1"
                                                    <?= isset($checkbox_data['major_grp_bank']) && $checkbox_data['major_grp_bank'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Trade Type:</td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">008 - One Time Supplier</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_one_time_supplier" id="major_grp_one_time_supplier" value="1"
                                                    <?= isset($checkbox_data['major_grp_ot_supplier']) && $checkbox_data['major_grp_ot_supplier'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">01 - Goods</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="trade_type_goods" id="trade_type_goods" value="1"
                                                    <?= isset($checkbox_data['trade_type_goods']) && $checkbox_data['trade_type_goods'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">009 - Government Offices</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_government_offices" id="major_grp_government_offices" value="1"
                                                    <?= isset($checkbox_data['major_grp_government_offices']) && $checkbox_data['major_grp_government_offices'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">02 - Services</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="trade_type_services" id="trade_type_services" value="1"
                                                    <?= isset($checkbox_data['trade_type_services']) && $checkbox_data['trade_type_services'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">010 - Insurance</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_insurance" id="major_grp_insurance" value="1"
                                                    <?= isset($checkbox_data['major_grp_insurance']) && $checkbox_data['major_grp_insurance'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-start">03 - Goods/Services</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="trade_type_GoodsServices" id="trade_type_GoodsServices" value="1"
                                                    <?= isset($checkbox_data['trade_type_goods_services']) && $checkbox_data['trade_type_goods_services'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">011 - Employees</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_employees" id="major_grp_employees" value="1"
                                                    <?= isset($checkbox_data['major_grp_employees']) && $checkbox_data['major_grp_employees'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">012 - Sub/Affiliates/Intercompany</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_subs_affiliates" id="major_grp_subs_affiliates" value="1"
                                                    <?= isset($checkbox_data['major_grp_sub_aff_intercompany']) && $checkbox_data['major_grp_sub_aff_intercompany'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center" style="font-size: 20px;"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2" class="text-start">013 - Utilities</td>
                                                <td colspan="2" class="text-center">
                                                    <input type="checkbox" name="major_grp_utilities" id="major_grp_utilities" value="1"
                                                    <?= isset($checkbox_data['major_grp_utilities']) && $checkbox_data['major_grp_utilities'] == 1 ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier Code</label>
                                        <input type="text" name="supplier_code" id="supplier_code" value="<?php echo $supplier_code; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier Account Group</label>
                                        <input type="text" name="supplier_account_group" id="supplier_account_group" value="<?php echo $supplier_account_group; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier Name</label>
                                        <input type="text" name="supplier_name" id="supplier_name" value="<?php echo $supplier_name; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country Origin</label>
                                        <input type="text" name="country_origin" id="country_origin" value="<?php echo $country_origin; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Supplier Address</label>
                                        <input type="text" name="supplier_address" id="supplier_address" value="<?php echo $supplier_address; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Office Tel. No:</label>
                                        <input type="text" name="office_tel_no" id="office_tel_no" value="<?php echo $office_tel; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip Code:</label>
                                        <input type="number" name="zip_code" id="zip_code" value="<?php echo $zip_code; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person:</label>
                                        <input type="text" name="contact_person" id="contact_person" value="<?php echo $contact_person; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Terms:</label>
                                        <input type="text" name="terms" id="terms" value="<?php echo $terms; ?>" class="form-control select2" readonly>  
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tin No:</label>
                                        <input type="number" name="tin_no" id="tin_no" value="<?php echo $tin_no; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pricelist:</label>
                                        <input type="number" name="pricelist" id="pricelist" value="<?php echo $pricelist; ?>" class="form-control select2" readonly>  
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>A/P Account:</label>
                                        <input type="text" name="ap_account" id="ap_account" value="<?php echo $ap_account; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>EWT:</label>
                                        <input type="text" name="ewt" id="ewt" value="<?php echo $ewt; ?>" class="form-control select2" readonly>  
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Advance Account:</label>
                                        <input type="text" name="advance_acc" id="advance_acc" value="<?php echo $advance_account; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_classification">VAT:</label>
                                        <div class="d-flex align-items-center">
                                            <!-- Textbox -->
                                            <input type="number" name="vat" id="vat" value="<?php echo $vat; ?>" class="form-control" style="flex: 1;" readonly>
                                            <!-- Checkbox -->
                                            <div class="form-check ms-auto d-flex align-items-center" style="margin-left: auto; margin-top: 10px;">
                                                <label for="non_vat" class="form-check-label me-2" style="font-size: 20px; line-height: 1.5;">Non-VAT</label>
                                                    <input type="checkbox" name="checkbox_non_vat" id="checkbox_non_vat" class="form-check-input" style="width: 40px; height: 19px;" 
                                                    value="1" <?php echo ($non_vat == 1) ? 'checked' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payee 1</label>
                                        <input type="text" name="payee1" id="payee1" value="<?php echo $payee_1; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payee 2</label>
                                        <input type="text" name="payee2" id="payee2" value="<?php echo $payee_2; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payee 3</label>
                                        <input type="text" name="payee3" id="payee3" value="<?php echo $payee_3; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <hr class="divider"> 

                                <div class="design-text text-center">
                                    Note: This field is for 3PL suppliers only
                                </div>

                                <hr class="divider">

                                

                                <div class="design-text" style="text-align: left; padding-left: 20px;">
                                    Driver
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Driver Name</label>
                                        <input type="text" name="driver_name" id="driver_name" value="<?php echo $driver_name; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Driver Contact No:</label>
                                        <input type="text" name="driver_contact_no" id="driver_contact_no" value="<?php echo $driver_contact_no; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Driver Fleet:</label>
                                        <input type="text" name="driver_fleet" id="driver_fleet" value="<?php echo $driver_fleet; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Driver Plate No:</label>
                                        <input type="text" name="driver_plate_no" id="driver_plate_no" value="<?php echo $driver_plate_no; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="design-text" style="text-align: left; padding-left: 20px;">
                                    Helper
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Helper Name</label>
                                        <input type="text" name="helper_name" id="helper_name" value="<?php echo $helper_name; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Helper Contact No:</label>
                                        <input type="text" name="helper_contact_no" id="helper_contact_no" value="<?php echo $helper_contact_no; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Helper Rate Card:</label>
                                        <input type="text" name="helper_rate_card" id="helper_rate_card" value="<?php echo $helper_rate_card; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Requested By:</label>
                                        <input type="text" name="requested_by" id="requested_by" value="<?php echo $requested_by; ?>" class="form-control select2" readonly> 
                                    </div>
                                </div>
                                
                                <?php 
                                $role = $this->session->userdata('login_data')['role'];
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Approved By</label>
                                        <input type="text" name="approved_by" id="approved_by" value="<?php echo $approved_by; ?>" class="form-control select2" <?php echo ($role === 'L2') ? 'readonly' : ''; ?>> 
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="box-body pad">
                                            <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?php echo ($role === 'L2') ? 'disabled' : ''; ?>>Approved</button>
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

<!-- <p><strong>RECID:</strong> <?php echo $recid; ?></p> 
<p><strong>Ticket ID:</strong> <?php echo $ticket_id; ?></p> 
<p><strong>Requestor Name:</strong> <?php echo $requested_by; ?></p> 
<p><strong>Company:</strong> <?php echo implode(', ', $companies); ?></p>
<p><strong>Date:</strong> <?php echo $date; ?></p>
<p><strong>Non-Vat Value:</strong> <?php echo $non_vat; ?></p> -->
