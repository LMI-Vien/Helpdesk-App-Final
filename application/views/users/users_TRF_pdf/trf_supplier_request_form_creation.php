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

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Supplier Request Form TMS Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Supplier Request Form</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Supplier Request Form Tickets</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form id="trf_srf_form" action="<?= site_url('UsersTraccReq_controller/user_creation_supplier_request_form_pdf'); ?>" method="POST">
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
                                               
                                            <div class="row">
                                                <!-- Checkboxes Section -->
                                                <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                    <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="trf_comp_checkbox_value[]" value="LMI" id="checkbox_lmi">
                                                            <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="trf_comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi">
                                                            <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="trf_comp_checkbox_value[]" value="LPI" id="checkbox_lpi">
                                                            <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="trf_comp_checkbox_value[]" value="SV" id="checkbox_sv">
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
                                                                <input type="checkbox" name="local_supplier_grp" id="local_supplier_grp" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">001 - Local Trade Vendors</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_local_trade_ven" id="major_grp_local_trade_ven" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">02 - Foreign</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="foreign_supplier_grp" id="foreign_supplier_grp" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">002 - Local Non-Trade Vendors</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_local_nontrade_ven" id="major_grp_local_nontrade_ven" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">003 - Foreign Trade Vendors</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_foreign_trade_ven" id="major_grp_foreign_trade_ven" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Supplier:</td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">004 - Foreign Non-Trade Vendors</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_foreign_nontrade_ven" id="major_grp_foreign_nontrade_ven" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">01 - Trade</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="supplier_trade" id="supplier_trade" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">005 - Local-Broker/Forwarder</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_local_broker_forwarder" id="major_grp_local_broker_forwarder" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">02 - Non-Trade</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="supplier_non_trade" id="supplier_non_trade" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">006 - Rental</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_rental" id="major_grp_rental" value="1">
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td colspan="2" class="text-start"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">007 - Bank</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_bank" id="major_grp_bank" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-center" style="font-size: 20px; font-weight: bold;">Trade Type:</td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">008 - One Time Supplier</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_one_time_supplier" id="major_grp_one_time_supplier" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">01 - Goods</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="trade_type_goods" id="trade_type_goods" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">009 - Government Offices</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_government_offices" id="major_grp_government_offices" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">02 - Services</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="trade_type_services" id="trade_type_services" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">010 - Insurance</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_insurance" id="major_grp_insurance" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-start">03 - Goods/Services</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" name="trade_type_GoodsServices" id="trade_type_GoodsServices" value="1">
                                                            </td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">011 - Employees</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_employees" id="major_grp_employees" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">012 - Sub/Affiliates/Intercompany</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_subs_affiliates" id="major_grp_subs_affiliates" value="1">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-center" style="font-size: 20px;"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-center"></td>
                                                            <td colspan="2" class="text-start">013 - Utilities</td>
                                                            <td colspan="2" class="text-center">
                                                                <input type="checkbox" class="ms-auto" name="major_grp_utilities" id="major_grp_utilities" value="1">
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>


                                            <!-- <div class="col-md-12" style="margin-top: 10px; margin-left: 50px;">
                                                <div class="form-group">
                                                    <label for="supplier_group" class="custom-label" style="font-size: 20px;">Supplier Group:</label>
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="local_supplier_grp" class="mb-0 supplier-group-label checkbox-label-sup">01 - Local</label>
                                                            <input type="checkbox" name="local_supplier_grp" id="local_supplier_grp" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="foreign_supplier_grp" class="mb-0 supplier-group-label checkbox-label-sup">02 - Foreign</label>
                                                            <input type="checkbox" name="foreign_supplier_grp" id="foreign_supplier_grp" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <!-- <div class="col-md-12" style="margin-top: 10px; margin-left: 50px;">
                                                <div class="form-group">
                                                    <label for="" class="custom-label" style="font-size: 20px;">Supplier:</label>
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="supplier_trade" class="mb-0 supplier-group-label checkbox-label-sup">01 - Trade</label>
                                                            <input type="checkbox" name="supplier_trade" id="supplier_trade" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="supplier_non_trade" class="mb-0 supplier-group-label checkbox-label-sup">02 - Non-Trade</label>
                                                            <input type="checkbox" name="supplier_non_trade" id="supplier_non_trade" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <!-- <div class="col-md-12" style="margin-top: 10px; margin-left: 50px;">
                                                <div class="form-group">
                                                    <label for="" class="custom-label" style="font-size: 20px;">Trade Type:</label>
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="trade_type_goods" class="mb-0 supplier-group-label checkbox-label-sup">01 - Goods</label>
                                                            <input type="checkbox" name="trade_type_goods" id="trade_type_goods" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="trade_type_services" class="mb-0 supplier-group-label checkbox-label-sup">02 - Services</label>
                                                            <input type="checkbox" name="trade_type_services" id="trade_type_services" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-sup">
                                                            <label for="trade_type_GoodsServices" class="mb-0 supplier-group-label checkbox-label-sup">03 - Goods/Services</label>
                                                            <input type="checkbox" name="trade_type_GoodsServices" id="trade_type_GoodsServices" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <!-- <div class="col-md-12" style="margin-top: -370px; margin-left: 600px;">
                                                <div class="form-group">
                                                    <label for="supplier_group" class="custom-label text-end d-block" style="font-size: 20px;">Major Group:</label>
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_local_trade_ven" class="mb-0 major-group-label">001 - Local Trade Vendors</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_local_trade_ven" id="major_grp_local_trade_ven" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_local_nontrade_ven" class="mb-0 major-group-label">002 - Local Non-Trade Vendors</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_local_nontrade_ven" id="major_grp_local_nontrade_ven" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_foreign_trade_ven" class="mb-0 major-group-label">003 - Foreign Trade Vendors</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_foreign_trade_ven" id="major_grp_foreign_trade_ven" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_foreign_nontrade_ven" class="mb-0 major-group-label">004 - Foreign Non-Trade Vendors</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_foreign_nontrade_ven" id="major_grp_foreign_nontrade_ven" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_local_broker_forwarder" class="mb-0 major-group-label">005 - Local-Broker/Forwarder</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_local_broker_forwarder" id="major_grp_local_broker_forwarder" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_rental" class="mb-0 major-group-label">006 - Rental</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_rental" id="major_grp_rental" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_bank" class="mb-0 major-group-label">007 - Bank</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_bank" id="major_grp_bank" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_one_time_supplier" class="mb-0 major-group-label">008 - One Time Supplier</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_one_time_supplier" id="major_grp_one_time_supplier" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_government_offices" class="mb-0 major-group-label">009 - Government Offices</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_government_offices" id="major_grp_government_offices" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_insurance" class="mb-0 major-group-label">010 - Insurance</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_insurance" id="major_grp_insurance" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_employees" class="mb-0 major-group-label">011 - Employees</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_employees" id="major_grp_employees" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_subs_affiliates" class="mb-0 major-group-label">012 - Sub/Affiliates/Intercompany</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_subs_affiliates" id="major_grp_subs_affiliates" value="1">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center custom-checkbox-major-grp">
                                                            <label for="major_grp_utilities" class="mb-0 major-group-label">013 - Utilities</label>
                                                            <input type="checkbox" class="ms-auto" name="major_grp_utilities" id="major_grp_utilities" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                            <input type="hidden" name="supplier_code" id="supplier_code" value="-" class="form-control select2"> 
                                            <input type="hidden" name="supplier_account_group" id="supplier_account_group" value="-" class="form-control select2"> 
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Name</label>
                                                    <input type="text" name="supplier_name" id="supplier_name" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Country Origin</label>
                                                    <input type="text" name="country_origin" id="country_origin" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Supplier Address</label>
                                                    <input type="text" name="supplier_address" id="supplier_address" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact No:</label>
                                                    <input type="text" name="office_tel_no" id="office_tel_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Zip Code:</label>
                                                    <input type="text" name="zip_code" id="zip_code" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact Person:</label>
                                                    <input type="text" name="contact_person" id="contact_person" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Terms:</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2">  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tin No:</label>
                                                    <input type="text" name="tin_no" id="tin_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pricelist:</label>
                                                    <input type="text" name="pricelist" id="pricelist" value="" min=0.01 step="0.01" class="form-control select2">  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>A/P Account:</label>
                                                    <input type="text" name="ap_account" id="ap_account" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>EWT:</label>
                                                    <input type="text" name="ewt" id="ewt" value="" class="form-control select2">  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Advance Account:</label>
                                                    <input type="text" name="advance_acc" id="advance_acc" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="item_classification">VAT:</label>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Textbox -->
                                                        <input type="text" name="vat" id="vat" class="form-control" min=0 style="flex: 1;">
                                                        <!-- Checkbox -->
                                                        <div class="form-check ms-auto d-flex align-items-center" style="margin-left: auto; margin-top: 10px;">
                                                            <label for="non_vat" class="form-check-label me-2" style="font-size: 20px; line-height: 1.5;">Non-VAT</label>
                                                            <input type="checkbox" name="checkbox_non_vat" id="checkbox_non_vat" class="form-check-input" style="width: 40px; height: 19px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payee 1</label>
                                                    <input type="text" name="payee1" id="payee1" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payee 2</label>
                                                    <input type="text" name="payee2" id="payee2" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payee 3</label>
                                                    <input type="text" name="payee3" id="payee3" value="" class="form-control select2"> 
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
                                                    <label>Name</label>
                                                    <input type="text" name="driver_name" id="driver_name" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact No:</label>
                                                    <input type="text" name="driver_contact_no" id="driver_contact_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fleet:</label>
                                                    <input type="text" name="driver_fleet" id="driver_fleet" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Plate No:</label>
                                                    <input type="text" name="driver_plate_no" id="driver_plate_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="design-text" style="text-align: left; padding-left: 20px;">
                                              Helper
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="helper_name" id="helper_name" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact No:</label>
                                                    <input type="text" name="helper_contact_no" id="helper_contact_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Rate Card:</label>
                                                    <input type="text" name="helper_rate_card" id="helper_rate_card" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Requested By:</label>
                                                    <input type="text" name="requested_by" id="requested_by" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" required readonly> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="submitBtn" type="submit" class="btn btn-primary">Submit Ticket</button>
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
        var today = new Date().toISOString().split('T')[0];
        $('#date').val(today);

        $('#submitBtn').click(function (e) {
            e.preventDefault();

            var form = document.getElementById('trf_srf_form');
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
                    $('#trf_srf_form').submit(); 
                }
            });
        });
    });

    $('#tin_no, #zip_code').on('input', function () {
        this.value = this.value.replace(/[^0-9-]/g, '');
    });
   
</script>