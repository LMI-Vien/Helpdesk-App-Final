		<footer class="main-footer">
			<div class="pull-right hidden-xs">
	            <b>Version</b> 1.0.0
	        </div>
	        <strong>Copyright &copy; 2024 ICT - Lifestrong Marketing Inc. All rights reserved.</strong>
		</footer>
		<div id="sidebar-overlay"></div>
	</div>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">ICT Helpdesk Information Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <p><?php echo $this->session->flashdata('success'); ?></p>
                    <?php elseif ($this->session->flashdata('error')): ?>
                        <p><?php echo $this->session->flashdata('error'); ?></p>
                    <?php else: ?>
                        <p>No message available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">ICT Helpdesk Information Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Modal -->
    <div id="printMsrfModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Print Filtered Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Use the filters below to narrow down the data you want to print:</p>

                    <!-- Form for Filters -->
                    <form id="printFilterForm">
                        <!-- Date Range Filter -->
                        <div class="form-group">
                            <label for="startDate">Start Date:</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>

                        <!-- Status Filter -->
                        <div class="form-group">
                            <label for="statusFilter">Status:</label>
                            <select class="form-control" id="statusFilter" name="statusFilter" required>
                                <option value="" disabled selected>Statuses</option>
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPrint">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal MSRF -->
    <div class="modal fade" id="approve_modal_msrf" tabindex="-1" role="dialog" aria-labelledby="approve_modal_msrfLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="border: none; font-size: 20px; font-weight: bold; background: transparent; color:rgb(240, 240, 240); float: right;" aria-label="Close">
                        &times;
                    </button>
                    <h4 class="modal-title" id="approve_modal_msrfLabel">Approve Ticket</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body modal-padding-custom">
                        <div class="form-group">
                            <label>Requestor</label>
                            <input type="text" name="name" class="form-control form-value" style="width: 100%;" readonly id="inp_requestor">
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" name="name" class="form-control" style="width: 100%;" readonly id="inp_department">
                        </div>
                        <div class="form-group">
                            <label>Details Concern</label>
                            <textarea class="form-control" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly id="inp_concern"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Date Needed</label>
                            <input type="date" name="date_needed" id="inp_date_needed" class="form-control" value="" style="width: 100%;" readonly>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks" placeholder="" style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" id="msrf_inp_remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save-ticket" name="save_approve_ticket" data-module="msrf" data-status="Approved">Approve</button>
                    <button type="button" class="btn btn-danger save-ticket" name="save_disapprove_ticket" data-module="msrf" data-status="Rejected">Rejected</button>
                    <button type="button" class="btn btn-warning save-ticket" name="save_disapprove_ticket" data-module="msrf" data-status="Returned">Returned</button>
                </div>
            </div>
            </div>
    </div>

    <!-- Approve Modal TRACC Concern -->
    <div class="modal fade" id="approve_modal_tracc_concern" tabindex="-1" role="dialog" aria-labelledby="approve_modal_tracc_concernLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="border: none; font-size: 20px; font-weight: bold; background: transparent; color:rgb(255, 255, 255); float: right;" aria-label="Close">
                        &times;
                    </button>
                    <h4 class="modal-title" id="approve_modal_tracc_concernLabel">Approve Ticket</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body modal-padding-custom">
                        <div class="form-group">
                            <label>Reported by</label>
                            <input type="text" name="name" class="form-control form-value" style="width: 100%;" readonly id="inp_reported_by">
                        </div>
                        <div class="form-group">
                            <label>Details Concern</label>
                            <textarea class="form-control" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly id="inp_concern"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks" placeholder="" style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" id="tracc_request_inp_remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save-ticket" name="save_approve_ticket" data-module="tracc-concern" data-status="Approved">Approve</button>
                    <button type="button" class="btn btn-danger save-ticket" name="save_disapprove_ticket" data-module="tracc-concern" data-status="Rejected">Rejected</button>
                    <button type="button" class="btn btn-warning save-ticket" name="save_disapprove_ticket" data-module="tracc-concern" data-status="Returned">Returned</button>
                    
                </div>
            </div>
            </div>
    </div>

    <!-- Approve Modal TRACC Request -->
    <div class="modal fade" id="approve_modal_tracc_request" tabindex="-1" role="dialog" aria-labelledby="approve_modal_tracc_requestLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="border: none; font-size: 20px; font-weight: bold; background: transparent; color:rgb(255, 255, 255); float: right;" aria-label="Close">
                        &times;
                    </button>
                    <h4 class="modal-title" id="approve_modal_tracc_requestLabel">Approve Ticket</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body modal-padding-custom">
                        <div class="form-group">
                            <label>Requestor</label>
                            <input type="text" name="name" class="form-control form-value" style="width: 100%;" readonly id="inp_requestor">
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" name="name" class="form-control" style="width: 100%;" readonly id="inp_department">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks" placeholder="" style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" id="tracc_concern_inp_remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save-ticket" name="save_approve_ticket" data-module="tracc-request" data-status="Approved">Approve</button>
                    <button type="button" class="btn btn-danger save-ticket" name="save_disapprove_ticket" data-module="tracc-request" data-status="Rejected">Rejected</button>
                    <button type="button" class="btn btn-warning save-ticket" name="save_disapprove_ticket" data-module="tracc-request" data-status="Returned">Returned</button>
                </div>
            </div>
            </div>
    </div>

	<script src="<?php echo base_url();?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo base_url();?>/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= base_url(); ?>/assets/plugins/iCheck/icheck.min.js"></script>
	<script>
		$.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/raphael/raphael.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/morris.js/morris.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/moment/min/moment.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <script src="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="<?= base_url(); ?>assets/dist/dist/js/adminlte.min.js"></script>
    <script src="<?= base_url(); ?>/assets/dist/dist/js/pages/dashboard.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script type="text/javascript">
        var base_url = '<?= base_url();?>';
        $(document).ready(function() {

            const savedFilter = localStorage.getItem('datatable_filter') || '';

            

            <?php if($this->session->flashdata('success')): ?>
                $('#successModal').modal('show');
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                $('#errorModal').modal('show');
            <?php endif; ?>

            $('#it_approval_stat').on('change', function() {
                if (this.value == "Approved") {
                    $('#ictassign').show();
                    $('#reason').hide();
                } else if (this.value == "Rejected") {
                    $('#ictassign').hide();
                    $('#reason').show();
                }else if (this.value == "Resolved") {
                    $('#ictassign').hide(); 
                    $('#reason').hide(); 
                }
            });

            $(document).on('click', '.lock_btn', function() {
                var emp_id = $(this).data('empid');
                var emp_no = $(this).closest('tr').find('td:eq(0)').text();

                $('#modalLock').find('#employee_id').text(emp_no);
                $('#id').val(emp_id);

                $('#modalLock').modal('show');
            });

            $(document).on('click', '.unlock_btn', function() {
                var emp_id = $(this).data('empid');
                var emp_no = $(this).closest('tr').find('td:eq(0)').text();

                $('#modalUnlock').find('#employee_id').text(emp_no);
                $('#recid').val(emp_id);

                $('#modalUnlock').modal('show');
            });

            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

            $('input[type="checkbox"].minimal').on('ifChanged', function(event){
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#password').attr('type', 'text');
                    $('#cpassword').attr('type', 'text');
                } else {
                    $('#password').attr('type', 'password');    
                    $('#cpassword').attr('type', 'password');
                }
            });

            $('#tblUsers').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/employee",
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [{
                    text: 'Add Employee',
                    className: 'btn btn-info',
                    action: function (e, dt, node, config) {
                        // $("#modal-add-users").modal("show");
                        window.location.href = '<?= base_url(); ?>' + 'sys/admin/add/employee';
                    }
                }],
                "columnDefs": [{
                    'target': 2,
                    "data": "btn_action",
                    'orderable': false,
                    "className": "text-center"
                }]
            });

            let table = $('#tblTickets').DataTable({

                initComplete: function () {
                    if (savedFilter) {
                        table.search(savedFilter).draw();
                    }
                },
                //"searching": false,
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_msrf/",
                    "type": "POST",
                    "data": function(d) {
                        // Add additional filter parameters to the DataTables request
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.statusFilter = $('#statusFilter').val();
                        d.dept_id = "<?= $user_details['dept_id']; ?>";
                    }
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                /*"dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [
                    {
                        text: 'Print Data',
                        className: 'btn btn-primary',
                        action: function (e, dt, node, config) {
                            // Open the modal when the button is clicked
                            $('#printMsrfModal').modal('show');
                        }
                    }
                ]*/
            });

            // Handle the confirm print action inside the modal
            $('#confirmPrint').on('click', function() {
                // Prevent the modal from closing if the form is not valid
                var form = $('#printFilterForm')[0];
                
                // Check form validity
                if (!form.checkValidity()) {
                    form.reportValidity(); // Show validation errors
                    return; // Exit function if form is invalid
                }

                // Close the modal
                $('#printMsrfModal').modal('hide');

                // Redraw the table with new filter parameters
                table.ajax.reload(function() {
                    // After table is reloaded, trigger the print functionality
                    table.button('.buttons-print').trigger();
                }, false); // false to avoid resetting the page number
            });

            $('.input-sm').on('keyup', function () {
                const value = $(this).val();
                localStorage.setItem('datatable_filter', value);
                //console.log(localStorage);
            });


            $('#tblTicketsTraccConcern').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_tracc_concern", 
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false
            });

            $('#tblTicketsTraccRequest').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_tracc_request",
                    "type": "POST"
                }, 
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false
            });
            

            $('#tblDepartment').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_departments",
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [{
                    text: 'Add Department',
                    className: 'btn btn-info',
                    action: function (e, dt, node, config) {
                        // $("#modal-add-users").modal("show"); 
                        window.location.href = '<?= base_url(); ?>' + 'sys/admin/add/department';
                    }
                }],
                "columnDefs": [{
                    'target': 2,
                    "data": "btn_action",
                    'orderable': false,
                    "className": "text-center"
                }]
            });

            

        });

        $('#approve_modal_msrf').on('hidden.bs.modal', function (e) {
          $(this)
            .find("input,textarea,select")
               .val('')
               .end()
            .find("input[type=checkbox], input[type=radio]")
               .prop("checked", "")
               .end();
        })
        $(document).on('click','.approve-ticket', function(e){
            var module_name = '<?=$this->uri->segment(5);?>';
            console.log(module_name);
            data_id = $(this).attr('data-id');
            
            if(module_name.toLowerCase() === "tracc_concern"){

                reported_by = $(this).attr('data-reported-by');
                concern = $(this).attr('data-concern');

                $("#approve_modal_tracc_concern #inp_concern").val(concern);
                $("#approve_modal_tracc_concern #inp_reported_by").val(reported_by);
                $('#approve_modal_tracc_concern').modal('show');
            }else if(module_name.toLowerCase() === "msrf"){
                requestor = $(this).attr('data-requestor');
                date_needed = $(this).attr('data-date-needed');
                department = $(this).attr('data-department');
                concern = $(this).attr('data-concern');

                $("#approve_modal_msrf #inp_concern").val(concern);
                $("#approve_modal_msrf #inp_requestor").val(requestor);
                $("#approve_modal_msrf #inp_department").val(department);
                $("#approve_modal_msrf #inp_date_needed").val(date_needed);
                $('#approve_modal_msrf').modal('show');
            }else{
                requestor = $(this).attr('data-requestor');
                department = $(this).attr('data-department');

                $("#approve_modal_tracc_request #inp_requestor").val(requestor);    
                $("#approve_modal_tracc_request #inp_department").val(department);
                $('#approve_modal_tracc_request').modal('show');
            }
            
            $('.save-ticket').attr("data-id", data_id);
            
        });

        $(document).on('click','.save-ticket', function(e){
            id = $(this).attr("data-id");
            data_module = $(this).attr('data-module');
            data_remarks = "";
            if(data_module === "msrf"){
                data_remarks = $('#msrf_inp_remarks').val();
            }else if(data_module === "tracc_request"){
                data_remarks = $('#tracc_request_inp_remarks').val();    
            }else{
                data_remarks = $('#tracc_concern_inp_remarks').val();
            }
            
            data_status = $(this).attr('data-status');
            $.ajax({
                type: 'POST',
                url: base_url + "Main/save_ticket", 
                data: {recid:id, data_module: data_module, data_remarks:data_remarks, data_status:data_status}, 
                dataType: 'json',
                success: function(response) {
                    if(response){
                        $('#approve_modal_tracc_concern').modal('hide');
                        $('#approve_modal_tracc_request').modal('hide');
                        $('#approve_modal_msrf').modal('hide');
                        if(response.status === "success"){
                            location.reload();
                        }else{
                            alert("Invalid");
                        }
                    }

                },
                error: function() {
                }
            });
        });

    function is_json(str) {
        try {
            if (/MSIE 9/i.test(navigator.userAgent)) {
                return JSON.parse($.trim(str));
            }else{
                var jparse = JSON.parse($.trim(str));
                if(Object.values(jparse)[0] == 'Asterisk is not allowed!'){
                    alert(Object.values(jparse)[0]);
                }else{
                    return jparse;
                }
            }
        } catch (e) {
            return $.trim(str)
        }
    } 

    </script>
</body>
</html>