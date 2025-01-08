		<footer class="main-footer">
    		<div class="container">
      			<div class="pull-right hidden-xs">
        			<b>Version</b> 1.0.0
      			</div>
      			<strong>Copyright &copy; 2024-2025 <a href="#"><span style="color: #9a1a1f">ICT Helpdesk</span></a>.</strong> All rights
      			reserved.
    		</div>
  		</footer>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      				<div class="modal-header">
        				<h4 class="modal-title" id="myModalLabel">ICT Helpdesk Information</h4>
      				</div>
      				<div class="modal-body">
					  	<div class="modal-body modal-padding-custom">
							<div class="form-group">
        						<label>List of Tickets</label>
								<select name="concern" id="concern" class="form-control">
									<option>Please select concern tickets</option>
									<option value="MSRF">MSRF Form</option>
									<option value="TRACC">TRACC Form</option>
								</select>
							</div>
						</div>
      				</div>
      				<div class="modal-footer">
						<button type="button" class="btn btn-success" name="concern_btn" id="concern_btn">Next</button>
        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      				</div>
    			</div>
  			</div>
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
					<?php 
					$successMessage = $this->session->flashdata('success');
					
					if (is_array($successMessage)) {
						echo implode(', ', $successMessage);
					} else {
						echo $successMessage;
					}
					?>
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

	<!-- Modal for Success -->
	<?php if ($this->input->get('success')): ?>
		<?php echo urldecode($this->input->get('success')); ?>
	<?php endif; ?>

	<!-- Modal for Error -->
	<?php if ($this->input->get('error')): ?>
		<?php echo urldecode($this->input->get('error')); ?>
	<?php endif; ?>

	<!-- Modal for Success -->
	<div id="modal-success-users" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<p>Success! Your message here.</p>
		</div>
	</div>

	<!-- Modal for Error -->
	<div id="modal-error-users" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<p>Error occurred. Please try again.</p>
		</div>
	</div>

	<script src="<?php echo base_url();?>assets/dist/users_template/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/fastclick/lib/fastclick.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/adminlte.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/demo.js"></script>
	<script src="<?= base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/ckeditor/ckeditor.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
	<script>
		var dept_id = <?php echo json_encode($user_details['dept_id']); ?>;
	</script>
	<script>

		// $(function() {
        //     //CKEDITOR.replace('editor1')
        //     $('.textarea').wysihtml5();
            
        //     $('#category').change(function() {
        //     	var val = $(this).val();
        //     	if(val === "others") {
        //     		$("#specify").show();
        //     	} else {
		//             $("#specify").hide();
		//         }
        //     });
        // });

		$(document).ready(function() {
			let status = '';

			<?php if($this->session->flashdata('success')): ?>
                $('#successModal').modal('show');
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                $('#errorModal').modal('show');
            <?php endif; ?>

			//console.log("JavaScript is running");
			$(".close").click(function(){
				$("#modal-success-users, #modal-error-users").modal("hide");
			});
			
			$('#it_status').on('change', function() {
				var val = $(this).val();
				if(val === "") {
					$('#form-add-submit-button').prop("disabled", true);
				} else {
					$('#form-add-submit-button').prop("disabled", false);
				}
			});

			$('#status_requestor').on('change', function() {
				var val = $(this).val();
				if(val === "") {
					$('#form-add-submit-button').prop("disabled", true);
				} else {
					$('#form-add-submit-button').prop("disabled", false);
				}
			});

			$('#status_users').on('change', function() {
				var val = $(this).val();
				if(val === "") {
					$('#form-add-submit-button').prop("disabled", true);
				} else {
					$('#form-add-submit-button').prop("disabled", false);
				}
			});

			$('#concern_btn').click(function() {
				var selectedValue = $('#concern').val();

				if (selectedValue == "MSRF") {
					window.location.href = "<?= base_url(); ?>sys/users/create/tickets/msrf";
				} else if (selectedValue == "TRACC") {
					window.location.href = "<?= base_url(); ?>sys/users/create/tickets/tracc_concern";
				} else {
					alert("Please Selected for creation of tickets");
				}
			});

			/*var buttonsConfig = [];
			if (dept_id == 1) {
                console.log(dept_id);
            } else {
				buttonsConfig.push({
                    text: 'Create Tickets',
                    className: 'btn btn-info',
                    action: function (e, dt, node, config) {
                        //window.location.href = '<?= base_url(); ?>' + 'sys/users/create/tickets/msrf';
                    }
                });
			}*/

			$('#tblMsrfConcern').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_msrf_ticket",
					"type": "POST"
				},
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
				
				// Define buttonsConfig and apply conditional logic for dept_id
				"buttons": (function(){
					var buttonsConfig = [];
					if (dept_id == 1){
						console.log(dept_id);
					}else{
						buttonsConfig.push({
							text: 'Create Tickets',
							className: 'btn btn-primary',
							action: function (e, dt, node, config){
								window.location.href = '<?= base_url(); ?>sys/users/create/tickets/tracc_concern';
							},
							attr: {
									style: 'background-color: #9a1b1e; color: #ffffff; border: none; height: 35px; border-radius: 4px; padding: 6px 12px;'
							}
						});
					}
					return buttonsConfig;
				})(),

				"columnDefs": [{
					'target': 4,
					'orderable': false,
					"data": "btn_action",
					"className": "text-center"
				}]
			});
			

			$('#tblTraccConcern').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_tracc_concern_ticket",
					"type": "POST"
				},
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
				
				"buttons": (function(){
					var buttonsConfig = [];
					if (dept_id == 1){
						console.log(dept_id);
					}else{
						buttonsConfig.push({
							text: 'Create Tickets',
							className: 'btn btn-primary',
							action: function (e, dt, node, config){
								window.location.href = '<?= base_url(); ?>sys/users/create/tickets/tracc_concern';
							},
							attr: {
									style: 'background-color: #9a1b1e; color: #ffffff; border: none; height: 35px; border-radius: 4px; padding: 6px 12px;'
							}
						});
					}
					return buttonsConfig;
				})(),
				"columnDefs": [{
					'target': 4,
					'orderable': false,
					"data": "btn_action",
					"className": "text-center"
				}]
			});

			$('#tblTraccRequest').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_trf_ticket",
					"type": "POST"
				},
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',

				"buttons": (function(){
					var buttonsConfig = [];
					if (dept_id == 1){
						console.log(dept_id);
					}else{
						buttonsConfig.push({
							text: 'Create Tickets',
							className: 'btn btn-primary',
							action: function (e, dt, node, config){
								window.location.href = '<?= base_url(); ?>sys/users/create/tickets/tracc_request';
							},
							attr: {
									style: 'background-color: #9a1b1e; color: #ffffff; border: none; height: 35px; border-radius: 4px; padding: 6px 12px;'
							}
						});
					}
					return buttonsConfig;
				})(),

				"columnDefs": [{
					'target': 4,
					'orderable': false,
					"data": "btn_action",
					"className": "text-center"
				}]
			});

			
			
			$('#tblMsrf').DataTable({
				"destroy": true,
				"info": false,
				"searching": false,
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/display_msrf",
					"type": "POST",
					"data": function(d) {
						d.status = $('#msrfStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "status" },
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});
			
			$('#tblConcerns').DataTable({
				"destroy": true,
				"info": false,
				"searching": false,
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/display_concerns",
					"type": "POST",
					"data": function(d) {
						d.status = $('#concernStatus').val();
					}
				},
				"columns": [
					{ "data": "control_number" },
					{ "data": "status" },
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#tblRequests').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/display_requests",
					"type": "POST",
					"data": function(d) {
						d.status = $('#requestStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "status" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#tblTRRF').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_customer_request_form_rf",
					"type": "POST",
					"data": function(d) {
						d.status = $('#trStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "remarks" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#rf').show();
			$('#rfbutton').hide();

			$('#rfbutton').click(function() {
				$('#rf').show();
				$('#ss').hide();
				$('#ir').hide();
				$('#er').hide();
				$('#sr').hide();

				$('#rfbutton').hide();
				$('#ssbutton').show();
				$('#irbutton').show();
				$('#erbutton').show();
				$('#srbutton').show();
			});

			$('#tblTRSS').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_customer_request_form_ss",
					"type": "POST",
					"data": function(d) {
						d.status = $('#trStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "remarks" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#ss').hide();

			$('#ssbutton').click(function() {
				$('#rf').hide();
				$('#ss').show();
				$('#ir').hide();
				$('#er').hide();
				$('#sr').hide();

				$('#rfbutton').show();
				$('#ssbutton').hide();
				$('#irbutton').show();
				$('#erbutton').show();
				$('#srbutton').show();
			});

			$('#tblTRIR').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_customer_request_form_ir",
					"type": "POST",
					"data": function(d) {
						d.status = $('#trStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "remarks" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#ir').hide();

			$('#irbutton').click(function() {
				$('#rf').hide();
				$('#ss').hide();
				$('#ir').show();
				$('#er').hide();
				$('#sr').hide();

				$('#rfbutton').show();
				$('#ssbutton').show();
				$('#irbutton').hide();
				$('#erbutton').show();
				$('#srbutton').show();
			});

			$('#tblTRER').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_customer_request_form_er",
					"type": "POST",
					"data": function(d) {
						d.status = $('#trStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "remarks" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#er').hide();

			$('#erbutton').click(function() {
				$('#rf').hide();
				$('#ss').hide();
				$('#ir').hide();
				$('#er').show();
				$('#sr').hide();

				$('#rfbutton').show();
				$('#ssbutton').show();
				$('#irbutton').show();
				$('#erbutton').hide();
				$('#srbutton').show();
			});

			$('#tblTRSR').DataTable({
				"serverSide": true,
				"processing": true,
				"ajax": {
					"url": "<?= base_url(); ?>DataTables/get_customer_request_form_sr",
					"type": "POST",
					"data": function(d) {
						d.status = $('#trStatus').val();
					}
				},
				"columns": [
					{ "data": "ticket_id" },
					{ "data": "remarks" }
				],
				"responsive": true,
				"autoWidth": false,
				"lengthChange": false,
				"dom": 'rltp',
				"pageLength": 5,
			});

			$('#sr').hide();

			$('#srbutton').click(function() {
				$('#rf').hide();
				$('#ss').hide();
				$('#ir').hide();
				$('#er').hide();
				$('#sr').show();

				$('#rfbutton').show();
				$('#ssbutton').show();
				$('#irbutton').show();
				$('#erbutton').show();
				$('#srbutton').hide();
			});
			
			$('#form-alert').delay(1000 * 60 * 5).hide(500);
			
			getDate();
			$('.teststs').click(function() {
            	alert("here");
       		});


        });

        function getDate() {
			var today = new Date();
			//document.getElementById("date_req").value = ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2) + '-' + today.getFullYear();
		}

		// window.onload = function() {
        //     getDate();
        // };

	</script>
</body>
</html>