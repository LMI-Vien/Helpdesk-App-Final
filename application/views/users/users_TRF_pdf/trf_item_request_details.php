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
                                        <form action="<?= site_url('sys/users/details/concern/customer_req_item_req/update/' . $reqForm['recid']); ?>" method="POST">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="ticket_id" value="<?= $reqForm['ticket_id']; ?>" readonly />
                                            </div>

                                                <!-- Checkboxes Section -->
                                            <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LMI" id="checkbox_lmi" <?= in_array('LMI', $companies) ? 'checked' : ''; ?>>
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi" <?= in_array('RGDI', $companies) ? 'checked' : ''; ?>>
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LPI" id="checkbox_lpi" <?= in_array('LPI', $companies) ? 'checked' : ''; ?>>
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="SV" id="checkbox_sv" <?= in_array('SV', $companies) ? 'checked' : ''; ?>>
                                                        <label for="checkbox_sv" class="checkbox-label">SVI</label>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <!-- Date Input Section (Label beside the date input) -->
                                            <div class="col-md-3" style="margin-top: 15px;">
                                                <div class="form-group d-flex align-items-center custom-form-group">
                                                    <label for="date" class="mr-2">Date</label>
                                                    <input type="date" name="date" id="date" class="form-control" value="<?= $reqForm['date']; ?>" required>
                                                </div>
                                            </div>
                        
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>LMI Item Code</label>
                                                    <input type="text" name="lmi_item_code" id="lmi_item_code" value="<?= $reqForm['lmi_item_code']; ?>" class="form-control select2" required oninput="this.value = this.value.toUpperCase();"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Long Description (<small style="font-size:0.8em; color: red;">100 max char.</small>)</label>
                                                    <input type="text" name="long_description" id="long_description" value="<?= $reqForm['long_description']; ?>" class="form-control select2" maxlength="100"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Short Description (<small style="font-size:0.8em; color: red;">50 max char.</small>)</label>
                                                    <input type="text" name="short_description" id="short_description" value="<?= $reqForm['short_description']; ?>" class="form-control select2" maxlength="50"> 
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
                                                                    <input type="checkbox" name="checkbox_inventory" id="checkbox_inventory" value="1" <?= $checkboxes['inventory'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_inventory">Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_non_inventory" id="checkbox_non_inventory" value="1" <?= $checkboxes['non_inventory'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_non_inventory">Non-Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_services" id="checkbox_services" value="1" <?= $checkboxes['services'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_services">Services</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_charges" id="checkbox_charges" value="1" <?= $checkboxes['charges'] ? 'checked' : ''; ?>>
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
                                                                    <input type="checkbox" name="checkbox_watsons" id="checkbox_watsons" value="1" <?= $checkboxes['watsons'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_watsons">Watsons</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_other_accounts" id="checkbox_other_accounts" value="1" <?= $checkboxes['other_accounts'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_other_accounts">Other Accounts</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_online" id="checkbox_online" value="1" <?= $checkboxes['online'] ? 'checked' : ''; ?>>
                                                                    <label for="checkbox_online">Online</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="checkbox_all_accounts" id="checkbox_all_accounts" value="1" <?= $checkboxes['all_accounts'] ? 'checked' : ''; ?>>
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
                                                                <input type="checkbox" name="checkbox_trade" id="checkbox_trade" value="1" <?= $checkboxes['trade'] ? 'checked' : ''; ?>>
                                                                <label for="checkbox_trade">Trade</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_non_trade" id="checkbox_non_trade" value="1" <?= $checkboxes['non_trade'] ? 'checked' : ''; ?>>
                                                                <label for="checkbox_non_trade">Non-Trade</label>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label class="custom-label">Type:</label>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_trade_type" id="radio_trade" value="trade" <?= isset($checkboxes['trade']) && $checkboxes['trade'] == 1 ? 'checked' : ''; ?>>
                                                                <label for="radio_trade">Trade</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_trade_type" id="radio_non_trade" value="non_trade" <?= isset($checkboxes['trade']) && $checkboxes['trade'] == 0 ? 'checked' : ''; ?>>
                                                                <label for="radio_non_trade">Non-Trade</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Batch Required Checkboxes -->
                                                    <!-- <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label for="" class="custom-label">Batch Required?</label>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_batch_required_yes" id="checkbox_batch_required_yes" value="1" <?= $checkboxes['yes'] ? 'checked' : ''; ?>>
                                                                <label for="checkbox_batch_required_yes">YES</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="checkbox_batch_required_no" id="checkbox_batch_required_no" value="1" <?= $checkboxes['no'] ? 'checked' : ''; ?>>
                                                                <label for="checkbox_batch_required_no">NO</label>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label class="custom-label">Batch Required?</label>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_batch_required" id="radio_batch_required_yes" value="yes" <?= isset($checkboxes['yes']) && $checkboxes['yes'] == 1 ? 'checked' : ''; ?>>
                                                                <label for="radio_batch_required_yes">YES</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="radio" name="radio_batch_required" id="radio_batch_required_no" value="no" <?= isset($checkboxes['yes']) && $checkboxes['yes'] == 0 ? 'checked' : ''; ?>>
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
                                                    <input type="text" name="item_classification" id="item_classification" value="<?= $reqForm['item_classification']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub-Classification</label>
                                                    <input type="text" name="item_sub_classification" id="item_sub_classification" value="<?= $reqForm['item_sub_classification']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <input type="text" name="department" id="department" value="<?= $reqForm['department']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Merch Category</label>
                                                    <input type="text" name="merch_cat" id="merch_cat" value="<?= $reqForm['merch_category']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Brand</label>
                                                    <input type="text" name="brand" id="brand" value="<?= $reqForm['brand']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Code</label>
                                                    <input type="text" name="supplier_code" id="supplier_code" value="<?= $reqForm['supplier_code']; ?>" class="form-control select2" oninput="this.value = this.value.toUpperCase();"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Name</label>
                                                    <input type="text" name="supplier_name" id="supplier_name" value="<?= $reqForm['supplier_name']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Class</label>
                                                    <input type="text" name="class" id="class" value="<?= $reqForm['class']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tag</label>
                                                    <input type="text" name="tag" id="tag" value="<?= $reqForm['tag']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Source</label>
                                                    <input type="text" name="source" id="source" value="<?= $reqForm['source']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>HS Code</label>
                                                    <input type="text" name="hs_code" id="hs_code" value="<?= $reqForm['hs_code']; ?>" class="form-control select2"> 
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
                                                    <input type="number" name="unit_cost" id="unit_cost" value="<?= $reqForm['unit_cost']; ?>" class="form-control select2" min="0" step="0.01"> 
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
                                                    <input type="number" name="selling_price" id="selling_price" value="<?= $reqForm['selling_price']; ?>" class="form-control select2" min="0" step="0.01"> 
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
                                                    <input type="text" name="major_item_group" id="major_item_group" value="<?= $reqForm['major_item_group']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub. Group</label>
                                                    <input type="text" name="item_sub_group" id="item_sub_group" value="<?= $reqForm['item_sub_group']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Account Type</label>
                                                    <input type="text" name="account_type" id="account_type" value="<?= $reqForm['account_type']; ?>" class="form-control select2"> 
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
                                                    <input type="text" name="sales" id="sales" value="<?= $reqForm['sales']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Return</label>
                                                    <input type="text" name="sales_return" id="sales_return" value="<?= $reqForm['sales_return']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchases</label>
                                                    <input type="text" name="purchases" id="purchases" value="<?= $reqForm['purchases']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchase Return</label>
                                                    <input type="text" name="purchase_return" id="purchase_return" value="<?= $reqForm['purchase_return']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CGS</label>
                                                    <input type="text" name="cgs" id="cgs" value="<?= $reqForm['cgs']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Inventory</label>
                                                    <input type="text" name="inventory" id="inventory" value="<?= $reqForm['inventory']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Disc.</label>
                                                    <input type="text" name="sales_disc" id="sales_disc" value="<?= $reqForm['sales_disc']; ?>" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GL Department</label>
                                                    <input type="text" name="gl_dept" id="gl_dept" value="<?= $reqForm['gl_department']; ?>" class="form-control select2"> 
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
                                                        <?php foreach($checkboxes2 as $key => $item): ?>
                                                            <tr>
                                                                <td style="display: none"><input type="hidden" name="rows_gl[<?= $key; ?>][recid]" value="<?= $item['recid']; ?>"></td> 
                                                                <td><input type="text" class="form-control" name="rows_gl[<?= $key; ?>][uom]" value="<?= $item['uom']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_gl[<?= $key; ?>][barcode]" value="<?= $item['barcode']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl[<?= $key; ?>][length]" value="<?= $item['length']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl[<?= $key; ?>][height]" value="<?= $item['height']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl[<?= $key; ?>][width]" value="<?= $item['width']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl[<?= $key; ?>][weight]" value="<?= $item['weight']; ?>" placeholder=""></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <?php for ($i = 0; $i < 4; $i++) : ?>
                                                            <tr>
                                                                <td><input type="text" class="form-control" name="rows_gl_new[<?= $i; ?>][uom]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_gl_new[<?= $i; ?>][barcode]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl_new[<?= $i; ?>][length]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl_new[<?= $i; ?>][height]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl_new[<?= $i; ?>][width]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_gl_new[<?= $i; ?>][weight]" placeholder=""></td>
                                                            </tr>
                                                        <?php endfor; ?>
                                                    </tbody>
                                                </table>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Capacity per Pallet <small style="font-size:0.8em;">(Smallest OUM (PCS))</small></label>
                                                    <input type="text" name="capacity_per_pallet" id="capacity_per_pallet" value="<?= $reqForm['capacity_per_pallet']; ?>" class="form-control select2"> 
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
                                                        <?php foreach($checkboxes3 as $key => $item): ?>
                                                            <tr>
                                                                <td style="display: none;"><input type="hidden" name="rows_whs[<?= $key; ?>][recid]" value="<?= $item['recid']; ?>"></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][warehouse]" value="<?= $item['warehouse']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][warehouse_no]" value="<?= $item['warehouse_no']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][storage_location]" value="<?= $item['storage_location']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][storage_type]" value="<?= $item['storage_type']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][fixed_bin]" value="<?= $item['fixed_bin']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs[<?= $key; ?>][min_qty]" value="<?= $item['min_qty']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs[<?= $key; ?>][max_qty]" value="<?= $item['max_qty']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs[<?= $key; ?>][replen_qty]" value="<?= $item['replen_qty']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs[<?= $key; ?>][control_qty]" value="<?= $item['control_qty']; ?>" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs[<?= $key; ?>][round_qty]" value="<?= $item['round_qty']; ?>" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs[<?= $key; ?>][uom]" value="<?= $item['uom']; ?>" placeholder=""></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                            <tr>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][warehouse]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][warehouse_no]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][storage_location]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][storage_type]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][fixed_bin]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs_new[<?= $i; ?>][min_qty]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs_new[<?= $i; ?>][max_qty]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs_new[<?= $i; ?>][replen_qty]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs_new[<?= $i; ?>][control_qty]" placeholder=""></td>
                                                                <td><input type="number" class="form-control" name="rows_whs_new[<?= $i; ?>][round_qty]" placeholder=""></td>
                                                                <td><input type="text" class="form-control" name="rows_whs_new[<?= $i; ?>][uom]" placeholder=""></td>
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
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Submit Tickets</button>
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
    });
</script>
