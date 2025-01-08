<?php 
	set_timezone();
	// echo set_timezone();
	$current_time = date('H:i');
	// print_r($current_time);
	// die();  

    $cutoff_time = '16:59';

    if ($current_time >= $cutoff_time){
        $disabled = "disabled";
    } else {
        $disabled = "";
    }

    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];

    // print_r($sess_login_data); die();
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

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Item Request Form Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Item Request Form Creation</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">New Item Request Form Tickets</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form id="trf_irf_form" action="<?= site_url('UsersTraccReq_controller/user_creation_item_request_form_pdf'); ?>" method="POST">
                                            <div class="col-md-12">
                                                <select name="trf_number" id="trf_number" class="form-control" required>
                                                    <option value="" disabled selected>Select Ticket Number</option>
                                                    <?php if (isset($ticket_numbers) && is_array($ticket_numbers)): ?>
                                                        <?php foreach ($ticket_numbers as $ticket): ?>
                                                            <option value="<?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES, 'UTF-8') ?>">
                                                                <?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES, 'UTF-8') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled>No Tickets Available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                                <!-- Checkboxes Section -->
                                            <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LMI" id="checkbox_lmi">
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi">
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LPI" id="checkbox_lpi">
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="SV" id="checkbox_sv">
                                                        <label for="checkbox_sv" class="checkbox-label">SVI</label>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <!-- Date Input Section (Label beside the date input) -->
                                            <div class="col-md-3" style="margin-top: 15px;">
                                                <div class="form-group d-flex align-items-center custom-form-group">
                                                    <label for="date" class="mr-2">Date</label>
                                                    <input type="date" name="date" id="date" class="form-control" required>
                                                </div>
                                            </div>
                        
                                            
                                            <!-- <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>LMI Item Code</label>
                                                    <input type="text" name="lmi_item_code" id="lmi_item_code" value="" class="form-control select2" oninput="this.value = this.value.toUpperCase();"> 
                                                </div>
                                            </div> -->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Long Description (<small style="font-size:0.8em; color: red;">100 max char.</small>)</label>
                                                    <input type="text" name="long_description" id="long_description" value="" class="form-control select2" maxlength="100"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Short Description (<small style="font-size:0.8em; color: red;">50 max char.</small>)</label>
                                                    <input type="text" name="short_description" id="short_description" value="" class="form-control select2" maxlength="50"> 
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
                                                                    <input type="checkbox" name="checkbox_inventory" id="checkbox_inventory" value="1">
                                                                    <label for="checkbox_inventory">Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_non_inventory" id="checkbox_non_inventory" value="1">
                                                                    <label for="checkbox_non_inventory">Non-Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_services" id="checkbox_services" value="1">
                                                                    <label for="checkbox_services">Services</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_charges" id="checkbox_charges" value="1">
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
                                                                    <input type="checkbox" name="checkbox_watsons" id="checkbox_watsons" value="1">
                                                                    <label for="checkbox_watsons">Watsons</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_other_accounts" id="checkbox_other_accounts" value="1">
                                                                    <label for="checkbox_other_accounts">Other Accounts</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_online" id="checkbox_online" value="1">
                                                                    <label for="checkbox_online">Online</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_all_accounts" id="checkbox_all_accounts" value="1">
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
                                                                <input type="checkbox" name="checkbox_trade" id="checkbox_trade" value="1">
                                                                <label for="checkbox_trade">Trade</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_non_trade" id="checkbox_non_trade" value="1">
                                                                <label for="checkbox_non_trade">Non-Trade</label>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <!-- Trade and Non-Trade Radios -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label class="custom-label">Type:</label>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_trade_type" id="radio_trade" value="trade">
                                                                <label for="radio_trade">Trade</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_trade_type" id="radio_non_trade" value="non_trade">
                                                                <label for="radio_non_trade">Non-Trade</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Batch Required Checkboxes -->
                                                    <!-- <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label for="" class="custom-label">Batch Required?</label>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_batch_required_yes" id="checkbox_batch_required_yes" value="1">
                                                                <label for="checkbox_batch_required_yes">YES</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_batch_required_no" id="checkbox_batch_required_no" value="1">
                                                                <label for="checkbox_batch_required_no">NO</label>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <!-- Batch Required Radios -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label class="custom-label">Batch Required?</label>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_batch_required" id="radio_batch_required_yes" value="yes">
                                                                <label for="radio_batch_required_yes">YES</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_batch_required" id="radio_batch_required_no" value="no">
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
                                                    <input type="text" name="item_classification" id="item_classification" value="" class="form-control select2"> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub-Classification</label>
                                                    <input type="text" name="item_sub_classification" id="item_sub_classification" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <input type="text" name="department" id="department" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Merch Category</label>
                                                    <input type="text" name="merch_cat" id="merch_cat" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Brand</label>
                                                    <input type="text" name="brand" id="brand" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Code</label>
                                                    <input type="text" name="supplier_code" id="supplier_code" value="" class="form-control select2" oninput="this.value = this.value.toUpperCase();"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Name</label>
                                                    <input type="text" name="supplier_name" id="supplier_name" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Class</label>
                                                    <input type="text" name="class" id="class" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tag</label>
                                                    <input type="text" name="tag" id="tag" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Source</label>
                                                    <input type="text" name="source" id="source" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>HS Code</label>
                                                    <input type="text" name="hs_code" id="hs_code" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Unit Cost</label>
                                                    <input type="text" name="unit_cost" id="unit_cost" value="" class="form-control select2"> 
                                                </div>
                                            </div> -->

                                            <div class="col-md-6"> 
                                                <div class="form-group"> 
                                                    <label for="unit_cost">Unit Cost</label> 
                                                    <input type="number" name="unit_cost" id="unit_cost" value="" class="form-control select2" min="0" step="0.01"> 
                                                    <small class="form-text text-muted">Please enter a valid unit cost.</small> 
                                                </div> 
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Selling Price</label>
                                                    <input type="text" name="selling_price" id="selling_price" value="" class="form-control select2"> 
                                                </div>
                                            </div> -->

                                            <div class="col-md-6"> 
                                                <div class="form-group"> 
                                                    <label for="selling_price">Selling Price</label> 
                                                    <input type="number" name="selling_price" id="selling_price" value="" class="form-control select2" min="0" step="0.01"> 
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
                                                    <input type="text" name="major_item_group" id="major_item_group" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub. Group</label>
                                                    <input type="text" name="item_sub_group" id="item_sub_group" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Account Type</label>
                                                    <input type="text" name="account_type" id="account_type" value="" class="form-control select2"> 
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
                                                    <input type="text" name="sales" id="sales" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Return</label>
                                                    <input type="text" name="sales_return" id="sales_return" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchases</label>
                                                    <input type="text" name="purchases" id="purchases" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchase Return</label>
                                                    <input type="text" name="purchase_return" id="purchase_return" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CGS</label>
                                                    <input type="text" name="cgs" id="cgs" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Inventory</label>
                                                    <input type="text" name="inventory" id="inventory" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Disc.</label>
                                                    <input type="text" name="sales_disc" id="sales_disc" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GL Department</label>
                                                    <input type="text" name="gl_dept" id="gl_dept" value="" class="form-control select2"> 
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
                                                    </tbody>
                                                </table>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Capacity per Pallet <small style="font-size:0.8em;">(Smallest OUM (PCS))</small></label>
                                                    <input type="text" name="capacity_per_pallet" id="capacity_per_pallet" value="" class="form-control select2"> 
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
                                                    </tbody>
                                                </table>
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
                                                        <button id="submitBtn" type="submit" class="btn btn-primary" <?=$disabled?>>Submit Tickets</button>
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
    $(document).ready(function(){ 
        // Function to handle the input validation
        function validateNumberInput(inputElement) {
            var value = inputElement.val();
            if (!/^\d*\.?\d*$/.test(value)) {
                inputElement.val(value.slice(0, -1)); // Remove the last character if it's not a number
            } 
        }

        // Attach the input event to both fields
        $('#unit_cost, #selling_price').on('input', function () {
            validateNumberInput($(this));
        });
  

        var today = new Date().toISOString().split('T')[0];
        $('#date').val(today);
        console.log(today);

        $('#submitBtn').click(function (e) {
            e.preventDefault();

            var form = document.getElementById('trf_irf_form');
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
                    $('#trf_irf_form').submit(); 
                }
            });
        });
    });
</script>
