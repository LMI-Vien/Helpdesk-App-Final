<!DOCTYPE html>
<html>
<head>
    <title>Service Request Form</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Service Request Form</h2>
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
    <?php echo form_open('service_requests/submit_request'); ?>
        <div class="form-group">
            <label for="msr_no">MSR#</label>
            <input type="text" class="form-control" id="msr_no" name="msr_no" value="MSRF-002" readonly>
        </div>
        <div class="form-group">
            <label for="requestor">Requestor</label>
            <input type="text" class="form-control" id="requestor" name="requestor" value="Gilbert Aaron Picardo Adane" readonly>
        </div>
        <div class="form-group">
            <label for="date_requested">Date Requested</label>
            <input type="date" class="form-control" id="date_requested" name="date_requested" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" class="form-control" id="department" name="department" value="Accounting" readonly>
        </div>
        <div class="form-group">
            <label for="date_needed">Date Needed</label>
            <input type="date" class="form-control" id="date_needed" name="date_needed">
        </div>
        <div class="form-group">
            <label for="asset_code">Asset Code</label>
            <input type="text" class="form-control" id="asset_code" name="asset_code">
        </div>
        <div class="form-group">
            <label for="request_category">Request Category</label>
            <select class="form-control" id="request_category" name="request_category">
                <option value="">Select Category</option>
                <option value="Computer">Computer (Laptop or Desktop)</option>
                <option value="Printer">Printer concerns</option>
                <option value="Network">Network or Internet connection</option>
                <option value="Projector">Projector / TV set-up</option>
                <option value="Others">Others, please specify</option>
            </select>
        </div>
        <div class="form-group">
            <label for="details_concern">Details Concern</label>
            <textarea class="form-control" id="details_concern" name="details_concern" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Tickets</button>
    <?php echo form_close(); ?>
</div>
</body>
</html>