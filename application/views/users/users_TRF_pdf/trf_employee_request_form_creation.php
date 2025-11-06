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
				Employee Request Form Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Employee Request Form Creation</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Employee Request Form Tickets</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form id="trf_erf_form" action="<?= site_url('UsersTraccReq_controller/user_creation_employee_request_form_pdf'); ?>" method="POST">
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

                                            <div class="col-md-12" style="margin-top: 20px;">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="employee_name" id="employee_name" value="" class="form-control select2" required> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select name="department" id="department" class="form-control select2" required>
                                                        <option value="" disabled selected>Select Department</option>
                                                        <?php if (!empty($departments)): ?>
                                                            <?php foreach ($departments as $dept): ?>
                                                                <option value="<?= htmlspecialchars($dept['recid'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                    <?= (isset($selectedDepartment) && $selectedDepartment == $dept['recid']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($dept['dept_desc'], ENT_QUOTES, 'UTF-8') ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <option value="" disabled>No departments available</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Position</label>
                                                    <input type="text" name="position" id="position" value="" class="form-control select2"> 
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" name="address" id="address" value="" class="form-control select2"> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Tel No. / Mobile No.</label>
                                                    <input type="text" name="tel_mobile_no" id="tel_mobile_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>TIN No.</label>
                                                    <input type="text" name="tin_no" id="tin_no" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Contact Person</label>
                                                    <input type="text" name="contact_person" id="contact_person" value="" class="form-control select2"> 
                                                </div>
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
                                                        <button id="submitBtn" type="submit" class="btn btn-primary">Submit Ticket</button>
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

            var form = document.getElementById('trf_erf_form');
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
                    $('#trf_erf_form').submit(); 
                }
            });
        });
    });

</script>
