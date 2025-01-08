<?php 
$sess_login_data = $this->session->userdata('login_data');
$role = $sess_login_data['role'];

    $disabled = "";
    $readonly = "";
    $btn_label = "Submit Ticket";
    if ($role === "L2") {
        $disabled = "disabled";
        $readonly = "readonly";
    } else {
        $disabled = "";
        $readonly = "";
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

    /* Custom Checkbox Group */
    .custom-checkbox-group {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px; /* Space between each checkbox container */
    }

    /* Individual Checkbox Container */
    .checkbox-container {
        display: flex;
        align-items: center;
        min-width: 150px; 
    }

    /* Align checkbox and label */
    .checkbox-container input[type="checkbox"] {
        width: 1.25em; 
        height: 1.25em;
        margin: 0;
        vertical-align: middle; 
    }

    /* Label Margin */
    .checkbox-container label {
        margin-left: 8px; 
        margin-bottom: 0; /
        vertical-align: middle; 
        font-size: 1.30em;
    }

    /* Move only the label */
    .custom-label {
        padding-right: 10px; 
        font-weight: bold; 
        text-align: left; 
        min-width: 120px; 
        font-size: 1.30em;
    }

    .design-text {
        font-size: 18px; /* Adjust the font size as needed */
        font-weight: bold; /* Optional: makes the text bold */
        color: #333; /* Optional: changes text color */
    }
    
    .container {
        max-width: 100%; /* Adjust the width of the container */
    }

    .table {
        font-size: 1.5rem; /* Smaller font size for the table text */
        width: 100%; /* Ensure table width fits within the container */
    }

    .table th, .table td {
        padding: 8px; /* Reduce padding for a more compact table */
        text-align: center; /* Center text in table cells */
    }

    .table .form-control {
        font-size: 1.2rem; /* Increase the font size */
        padding: 8px; /* Optional: Adjust padding for better visibility */
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
                                <form action="<?= site_url('AdminTraccReq_controller/approve_irf'); ?>" method="POST">
                                <input type="hidden" name="recid" id="recid" value="<?php echo $recid; ?>">
                                    <!-- Checkboxes Section -->
                                    <div class="col-md-7 text-center" style="margin-top: 15px;">
                                        <div class="form-group d-flex justify-content-center">
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
                                                <input type="checkbox" name="irf_comp_checkbox_value[]" value="" id="checkbox_<?php echo ($company); ?>"<?php echo in_array($company, $companies) ? 'checked' : ''; ?>>
                                                <label for="" class="checkbox-label"><?php echo $company; ?></label>
                                            </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>

                                    <!-- Date Input Section (Label beside the date input) -->
                                    <div class="col-md-3" style="margin-top: 15px;">
                                        <div class="form-group d-flex align-items-center custom-form-group">
                                            <label for="date" class="mr-2">Date</label>
                                            <input type="date" name="date" id="date" value="<?php echo $date; ?>" class="form-control" required readonly>
                                        </div>
                                    </div>
                
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>LMI Item Code</label>
                                            <input type="text" name="lmi_item_code" id="lmi_item_code" value="<?php echo $lmi_item_code; ?>" class="form-control select2" required oninput="this.value = this.value.toUpperCase();" <?=$readonly?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Long Description (<small style="font-size:0.8em; color: red;">100 max char.</small>)</label>
                                            <input type="text" name="long_description" id="long_description" value="<?php echo $long_description; ?>" class="form-control select2" maxlength="100" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Short Description (<small style="font-size:0.8em; color: red;">50 max char.</small>)</label>
                                            <input type="text" name="short_description" id="short_description" value="<?php echo $short_description; ?>" class="form-control select2" maxlength="50" readonly> 
                                        </div>
                                    </div>

                                    <hr class="divider">

                                    <!-- First Row of Checkboxes -->
                                    <div class="container mt-4">
                                        <div class="row justify-content-center">
                                            <!-- First Row of Checkboxes -->
                                            <div class="col-md-12 d-flex justify-content-center" style="margin-top: 5px;">
                                                <div class="form-group text-center">
                                                    <label for="" class="custom-label">Item Type:</label>
                                                    <div class="d-flex flex-wrap custom-checkbox-group justify-content-center">
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_inventory" id="checkbox_inventory" value="1"
                                                            <?= isset($checkbox_data1['inventory']) && $checkbox_data1['inventory'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_inventory">Inventory</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_non_inventory" id="checkbox_non_inventory" value="1"
                                                            <?= isset($checkbox_data1['non_inventory']) && $checkbox_data1['non_inventory'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_non_inventory">Non-Inventory</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_services" id="checkbox_services" value="1"
                                                            <?= isset($checkbox_data1['services']) && $checkbox_data1['services'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_services">Services</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_charges" id="checkbox_charges" value="1"
                                                            <?= isset($checkbox_data1['charges']) && $checkbox_data1['charges'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_charges">Charges</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Second Row of Checkboxes -->
                                            <div class="col-md-12 d-flex justify-content-center" style="margin-top: 5px;">
                                                <div class="form-group text-center">
                                                    <label for="" class="custom-label">Item Account:</label>
                                                    <div class="d-flex flex-wrap custom-checkbox-group justify-content-center">
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_watsons" id="checkbox_watsons" value="1"
                                                            <?= isset($checkbox_data1['watsons']) && $checkbox_data1['watsons'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_watsons">Watsons</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_other_accounts" id="checkbox_other_accounts" value="1"
                                                            <?= isset($checkbox_data1['other_accounts']) && $checkbox_data1['other_accounts'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_other_accounts">Other Accounts</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_online" id="checkbox_online" value="1"
                                                            <?= isset($checkbox_data1['online']) && $checkbox_data1['online'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_online">Online</label>
                                                        </div>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox" name="checkbox_all_accounts" id="checkbox_all_accounts" value="1"
                                                            <?= isset($checkbox_data1['all_accounts']) && $checkbox_data1['all_accounts'] == 1 ? 'checked' : ''; ?>>
                                                            <label for="checkbox_all_accounts">All Accounts</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="divider">

                                    <div class="container mt-4">
                                        <div class="row">
                                            <!-- Trade and Non-Trade Checkboxes -->
                                            <!-- <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                    <div class="checkbox-container">
                                                        <input type="checkbox" name="checkbox_trade" id="checkbox_trade" value="1"
                                                        <?= isset($checkbox_data1['trade']) && $checkbox_data1['trade'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="checkbox_trade">Trade</label>
                                                    </div>
                                                    <div class="checkbox-container">
                                                        <input type="checkbox" name="checkbox_non_trade" id="checkbox_non_trade" value="1"
                                                        <?= isset($checkbox_data1['non_trade']) && $checkbox_data1['non_trade'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="checkbox_non_trade">Non-Trade</label>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                    <label class="custom-label">Type:</label>
                                                    <div class="checkbox-container">
                                                        <input type="radio" name="radio_trade_type" id="radio_trade" value="trade" <?= isset($checkbox_data1['trade']) && $checkbox_data1['trade'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="radio_trade">Trade</label>
                                                    </div>
                                                    <div class="checkbox-container">
                                                        <input type="radio" name="radio_trade_type" id="radio_non_trade" value="non_trade" <?= isset($checkbox_data1['trade']) && $checkbox_data1['trade'] == 0 ? 'checked' : ''; ?>>
                                                        <label for="radio_non_trade">Non-Trade</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Batch Required Checkboxes -->
                                            <!-- <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                    <label for="" class="custom-label">Batch Required?</label>
                                                    <div class="checkbox-container">
                                                        <input type="checkbox" name="checkbox_batch_required_yes" id="checkbox_batch_required_yes" value="1"
                                                        <?= isset($checkbox_data1['yes']) && $checkbox_data1['yes'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="checkbox_batch_required_yes">YES</label>
                                                    </div>
                                                    <div class="checkbox-container">
                                                        <input type="checkbox" name="checkbox_batch_required_no" id="checkbox_batch_required_no" value="1"
                                                        <?= isset($checkbox_data1['no']) && $checkbox_data1['no'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="checkbox_batch_required_no">NO</label>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                    <label class="custom-label">Batch Required?</label>
                                                    <div class="checkbox-container">
                                                        <input type="radio" name="radio_batch_required" id="radio_batch_required_yes" value="yes" <?= isset($checkbox_data1['yes']) && $checkbox_data1['yes'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="radio_batch_required_yes">YES</label>
                                                    </div>
                                                    <div class="checkbox-container">
                                                        <input type="radio" name="radio_batch_required" id="radio_batch_required_no" value="no" <?= isset($checkbox_data1['yes']) && $checkbox_data1['yes'] == 0 ? 'checked' : ''; ?>>
                                                        <label for="radio_batch_required_no">NO</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <hr class="divider">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Item Classification</label>
                                            <input type="text" name="item_classification" id="item_classification" value="<?php echo $item_classification; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Item Sub-Classification</label>
                                            <input type="text" name="item_sub_classification" id="item_sub_classification" value="<?php echo $item_sub_classification; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <input type="text" name="department" id="department" value="<?php echo $department; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Merch Category</label>
                                            <input type="text" name="merch_cat" id="merch_cat" value="<?php echo $merch_category; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <input type="text" name="brand" id="brand" value="<?php echo $brand; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Supplier Code</label>
                                            <input type="text" name="supplier_code" id="supplier_code" value="<?php echo $supplier_code; ?>" class="form-control select2" oninput="this.value = this.value.toUpperCase();" readonly> 
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
                                            <label>Class</label>
                                            <input type="text" name="class" id="class" value="<?php echo $class; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tag</label>
                                            <input type="text" name="tag" id="tag" value="<?php echo $tag; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Source</label>
                                            <input type="text" name="source" id="source" value="<?php echo $source; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>HS Code</label>
                                            <input type="text" name="hs_code" id="hs_code" value="<?php echo $hs_code; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group"> 
                                            <label for="unit_cost">Unit Cost</label> 
                                            <input type="number" name="unit_cost" id="unit_cost" value="<?php echo $unit_cost; ?>" class="form-control select2" min="0" step="0.01" readonly> 
                                            <small class="form-text text-muted">Please enter a valid unit cost.</small> 
                                        </div> 
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group"> 
                                            <label for="selling_price">Selling Price</label> 
                                            <input type="number" name="selling_price" id="selling_price" value="<?php echo $selling_price; ?>" class="form-control select2" min="0" step="0.01" readonly> 
                                            <small class="form-text text-muted">Please enter a valid selling price.</small> 
                                        </div> 
                                    </div>

                                    <hr class="divider"> 

                                    <div class="design-text text-center">
                                        For Non-Invertoriable Items (Please Fill Up)
                                    </div>

                                    <hr class="divider">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Major Item Group</label>
                                            <input type="text" name="major_item_group" id="major_item_group" value="<?php echo $major_item_group; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Item Sub. Group</label>
                                            <input type="text" name="item_sub_group" id="item_sub_group" value="<?php echo $item_sub_group; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Type</label>
                                            <input type="text" name="account_type" id="account_type" value="<?php echo $account_type; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <hr class="divider"> 

                                    <div class="design-text text-center">
                                        GL Set-Up
                                    </div>

                                    <hr class="divider">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sales</label>
                                            <input type="text" name="sales" id="sales" value="<?php echo $sales; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sales Return</label>
                                            <input type="text" name="sales_return" id="sales_return" value="<?php echo $sales_return; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Purchases</label>
                                            <input type="text" name="purchases" id="purchases" value="<?php echo $purchases; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Purchase Return</label>
                                            <input type="text" name="purchase_return" id="purchase_return" value="<?php echo $purchase_return; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>CGS</label>
                                            <input type="text" name="cgs" id="cgs" value="<?php echo $cgs; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Inventory</label>
                                            <input type="text" name="inventory" id="inventory" value="<?php echo $inventory; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sales Disc.</label>
                                            <input type="text" name="sales_disc" id="sales_disc" value="<?php echo $sales_disc; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>GL Department</label>
                                            <input type="text" name="gl_dept" id="gl_dept" value="<?php echo $gl_department; ?>" class="form-control select2" readonly> 
                                        </div>
                                    </div>

                                    <div class="container mt-4">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>UOM</th>
                                                    <th>Barcode</th>
                                                    <th>Length (cm)</th>
                                                    <th>Height (cm)</th>
                                                    <th>Width (cm)</th>
                                                    <th>Weight (grams)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($checkbox_data2)): ?>
                                                    <?php foreach ($checkbox_data2 as $index => $row): ?>
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="rows_gl[<?= $index; ?>][uom]" value="<?= $row['uom']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_gl[<?= $index; ?>][barcode]" value="<?= $row['barcode']; ?>" placeholder="" readonly></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?= $index; ?>][length]" value="<?= $row['length']; ?>" placeholder="" readonly></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?= $index; ?>][height]" value="<?= $row['height']; ?>" placeholder="" readonly></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?= $index; ?>][width]" value="<?= $row['width']; ?>" placeholder="" readonly></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?= $index; ?>][weight]" value="<?= $row['weight']; ?>" placeholder="" readonly></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php for ($i = 0; $i < 4; $i++) : ?>
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="rows_gl[<?php echo $i; ?>][uom]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_gl[<?php echo $i; ?>][barcode]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?php echo $i; ?>][length]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?php echo $i; ?>][height]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?php echo $i; ?>][width]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_gl[<?php echo $i; ?>][weight]" placeholder=""></td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Capacity per Pallet <small style="font-size:0.8em;">(Smallest OUM (PCS))</small></label>
                                            <input type="text" name="capacity_per_pallet" id="capacity_per_pallet" value="<?php echo $capacity_per_pallet; ?>" class="form-control select2" readonly>  
                                        </div>
                                    </div>

                                    <hr class="divider"> 

                                    <div class="design-text text-center">
                                        Warehouse Set-Up
                                    </div>

                                    <hr class="divider">

                                    <div class="container mt-4">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Warehouse No.</th>
                                                    <th>Storage Location</th>
                                                    <th>Storage Type</th>
                                                    <th>Fixed Bin</th>
                                                    <th>Min. Qty</th>
                                                    <th>Max Qty</th>
                                                    <th>Replen. Qty</th>
                                                    <th>Control Qty</th>
                                                    <th>Round Qty</th>
                                                    <th>UOM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($checkbox_data3)): ?>
                                                    <?php foreach ($checkbox_data3 as $index => $row): ?>
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][warehouse]" value="<?= $row['warehouse']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][warehouse_no]" value="<?= $row['warehouse_no']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][storage_location]" value="<?= $row['storage_location']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][storage_type]" value="<?= $row['storage_type']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][fixed_bin]" value="<?= $row['fixed_bin']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][min_qty]" value="<?= $row['min_qty']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][max_qty]" value="<?= $row['max_qty']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][replen_qty]" value="<?= $row['replen_qty']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][control_qty]" value="<?= $row['control_qty']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][round_qty]" value="<?= $row['round_qty']; ?>" placeholder="" readonly></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?= $index; ?>][uom]" value="<?= $row['uom']; ?>" placeholder="" readonly></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][warehouse]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][warehouse_no]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][storage_location]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][storage_type]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][fixed_bin]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_whs[<?php echo $i; ?>][min_qty]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_whs[<?php echo $i; ?>][max_qty]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_whs[<?php echo $i; ?>][replen_qty]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_whs[<?php echo $i; ?>][control_qty]" placeholder=""></td>
                                                            <td><input type="number" class="form-control" name="rows_whs[<?php echo $i; ?>][round_qty]" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="rows_whs[<?php echo $i; ?>][uom]" placeholder=""></td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Requested By</label>
                                            <input type="text" name="requested_by" id="requested_by" value="<?php echo $requested_by; ?>" class="form-control select2" required readonly> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Approved By</label>
                                            <input type="text" name="approved_by" id="approved_by" value="<?php echo $approved_by; ?>" class="form-control select2" <?php echo ($role === 'L3') ? 'readonly' : ''; ?>> 
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="box-body pad">
                                                <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?php echo ($role === 'L3') ? 'disabled' : ''; ?>>Approved</button>
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
<p><strong>Date:</strong> <?php echo $date; ?></p> -->