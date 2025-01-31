<?php 
$role = $this->session->userdata('login_data')['role'];

$disabled = '';

if(!$user_id) {
    $disabled = 'disabled';
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
                                <?php if($role == "L2" && $user_id): ?>
                                    <form action="<?= site_url('AdminTraccReq_controller/admin_update_employee_request_form_pdf/' . $recid); ?>" method="POST">
                                <?php else: ?>
                                    <form action="<?= site_url('AdminTraccReq_controller/approve_erf'); ?>" method="POST">
                                <?php endif; ?>
                                <input type="hidden" name="recid" id="recid" value="<?php echo $recid; ?>">
                                <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $ticket_id; ?>">
                                <input type="hidden" name="approved" value="<?= $user_details['fname'] . ' ' . $user_details['lname']; ?>">
                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="employee_name" id="employee_name" value="<?php echo $name; ?>" class="form-control select2" required <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select name="department" id="department" value="<?php echo $department; ?>" class="form-control select2" required>

                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <input type="text" name="department" id="department" value="<?php echo $department_desc; ?>" class="form-control select2" <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" name="position" id="position" value="<?php echo $position; ?>" class="form-control select2" <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" id="address" value="<?php echo $address; ?>" class="form-control select2" <?= $disabled; ?>>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tel No. / Mobile No.</label>
                                            <input type="text" name="tel_mobile_no" id="tel_mobile_no" value="<?php echo $tel_no_mob_no; ?>" class="form-control select2" <?= $disabled; ?>> 
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>TIN No.</label>
                                            <input type="text" name="tin_no" id="tin_no" value="<?php echo $tin_no; ?>" class="form-control select2" <?= $disabled; ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Contact Person</label>
                                            <input type="text" name="contact_person" id="contact_person" value="<?php echo $contact_person; ?>" class="form-control select2" <?= $disabled; ?>> 
                                        </div>
                                    </div>  
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Requested By</label>
                                            <input type="text" name="requested_by" id="requested_by" value="<?php echo $requested_by; ?>" class="form-control select2" disabled> 
                                        </div>
                                    </div>

                                    <?php if($role == "L3"): ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Approved By</label>
                                                <input type="text" name="approved_by" id="approved_by" value="<?php echo $approved_by; ?>" class="form-control select2" <?php echo ($role === 'L3') ? 'readonly' : ''; ?>> 
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

