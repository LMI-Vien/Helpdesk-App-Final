<!DOCTYPE html>
<html>
<head>
    <title>TRACC Request Form</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>TRACC Request Form</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?php echo form_open('service_requests/submit_tracc_request'); ?>
        <div class="form-group">
            <label for="trf_no">TRF No</label>
            <input type="text" class="form-control" id="trf_no" name="trf_no" value="TRF-001" readonly>
        </div>
        <div class="form-group">
            <label for="requestor">Requestor</label>
            <input type="text" class="form-control" id="requestor" name="requestor">
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" class="form-control" id="department" name="department">
        </div>
        <div class="form-group">
            <label for="date_requested">Date Requested</label>
            <input type="date" class="form-control" id="date_requested" name="date_requested" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="date_needed">Date Needed</label>
            <input type="date" class="form-control" id="date_needed" name="date_needed">
        </div>
        <div class="form-group">
            <label for="master_file_tracc_access">Master File / Tracc Access</label>
            <select class="form-control" id="master_file_tracc_access" name="master_file_tracc_access">
                <option value="">Select Access</option>
                <option value="LMI">LMI</option>
                <option value="RGDI">RGDI</option>
                <option value="LPI">LPI</option>
                <option value="SV">SV</option>
            </select>
        </div>
        <div class="form-group">
            <label for="new_add_update">New/Add or Update</label>
            <select class="form-control" id="new_add_update" name="new_add_update">
                <option value="">Select Option</option>
                <option value="New">New</option>
                <option value="Add">Add</option>
                <option value="Update">Update</option>
            </select>
        </div>
        <div class="form-group">
            <label for="details_concern">Details Concern</label>
            <textarea class="form-control" id="details_concern" name="details_concern" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit TRACC Request</button>
    <?php echo form_close(); ?>
</div>
</body>
</html>