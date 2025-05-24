<?php 
	set_timezone();
	// echo set_timezone();
	$current_time = date('H.i');
	// print_r($current_time);
	// die();  
?>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
            <h1>
          		Dashboard
          		<small>Control Panel</small>
        	</h1>
			<ol class="breadcrumb">
			    <li><a href="<?= base_url(); ?>users/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="<?= base_url(); ?>users/dashboard">Dashboard</a></li>
			</ol>
        </section>
		<section class="content">
			<!-- Notification Bar -->
			<div class="notification-bar" id="notificationBar" style="display: none; position: absolute !important;">
				<span>⚠️ The cut-off time for submitting tickets is today at <?= $cutofftime->format('h:i'); ?>.</span>
				<button id="dismissNotification">Dismiss</button>
			</div>
			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<div class="summary-data" style="height: 200px; text-align: center;">
						<h3 style="margin-top: 50px; font-size: 30px; font-weight: bold;">Hello, <?= $user_details['fname']; ?>!</h3>
						<p>Today is <?= date("F j, Y, l"); ?></p>
					</div>
				</div>
				<div class="col-lg-6 col-xs-12">
					<div class="summary-data">
						<b>MSRF</b>
						<table class="summary-data-table">
							<tr>
								<td><b><i>Total:</i></b></td>
								<td align="right"><b><i><?= $msrf['count']; ?></i></b></td>
							</tr>
							<tr>
								<td>Open Tickets:</td>
								<td align="right"><?= $msrf['Open']; ?></td>
							</tr>
							<tr>
								<td>Approved Tickets:</td>
								<td align="right"><?= $msrf['Approved']; ?></td>
							</tr>
							<tr>
								<td>In Progress Tickets:</td>
								<td align="right"><?= $msrf['In Progress']; ?></td>
							</tr>
							<tr>
								<td>Resolved Tickets:</td>
								<td align="right"><?= $msrf['Resolved']; ?></td>
							</tr>
							<tr>
								<td>Closed Tickets:</td>
								<td align="right"><?= $msrf['Closed']; ?></td>
							</tr>
							<tr>
								<td>Rejected Tickets:</td>
								<td align="right"><?= $msrf['Rejected']; ?></td>
							</tr>
							<tr>
								<td>Returned Tickets:</td>
								<td align="right"><?= $msrf['Returned']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<div class="summary-data">
						<b>TRACC Concerns</b>
						<table class="summary-data-table">
							<tr>
								<td><b><i>Total:</i></b></td>
								<td align="right"><b><i><?= $concerns['count']; ?></i></b></td>
							</tr>
							<tr>
								<td>Open Tickets:</td>
								<td align="right"><?= $concerns['Open']; ?></td>
							</tr>
							<tr>
								<td>Approved Tickets:</td>
								<td align="right"><?= $concerns['Approved']; ?></td>
							</tr>
							<tr>
								<td>Done Tickets:</td>
								<td align="right"><?= $concerns['Done']; ?></td>
							</tr>
							<tr>
								<td>In Progress:</td>
								<td align="right"><?= $concerns['In Progress']; ?></td>
							</tr>
							<tr>
								<td>Resolved Tickets:</td>
								<td align="right"><?= $concerns['Resolved']; ?></td>
							</tr>
							<tr>
								<td>Closed Tickets:</td>
								<td align="right"><?= $concerns['Closed']; ?></td>
							</tr>
							<tr>
								<td>Rejected Tickets:</td>
								<td align="right"><?= $concerns['Rejected']; ?></td>
							</tr>
							<tr>
								<td>Returned Tickets:</td>
								<td align="right"><?= $concerns['Returned']; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-lg-6 col-xs-12">
					<div class="summary-data">
						<b>TRACC Requests</b>	
						<table class="summary-data-table">
							<tr>
								<td><b><i>Total:</i></b></td>
								<td align="right"><b><i><?= $requests['count']; ?></i></b></td>
							</tr>
							<tr>
								<td>Open Tickets:</td>
								<td align="right"><?= $requests['Open']; ?></td>
							</tr>
							<tr>
								<td>Approved Tickets:</td>
								<td align="right"><?= $requests['Approved']; ?></td>
							</tr>
							<tr>
								<td>In Progress Tickets:</td>
								<td align="right"><?= $requests['In Progress']; ?></td>
							</tr>
							<tr>
								<td>Resolved Tickets:</td>
								<td align="right"><?= $requests['Resolved']; ?></td>
							</tr>
							<tr>
								<td>Closed Tickets:</td>
								<td align="right"><?= $requests['Closed']; ?></td>
							</tr>
							<tr>
								<td>Rejected Tickets:</td>
								<td align="right"><?= $requests['Rejected']; ?></td>
							</tr>
							<tr>
								<td>Returned Tickers:</td>
								<td align="right"><?= $requests['Returned']; ?></td>
							</tr>
						</table><br>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<div class="table-data-summary">
						<?php
							if($this->session->flashdata('editTR')) {
								echo "<p><b>";
								echo $this->session->flashdata('editTR');
								echo "</b></p>";
							}
						?>
						<table style="width: 100%;">
							<tr>
								<td><button id="rfbutton">Customer Request</button></td>
								<td><button id="ssbutton">Shipping Setup</button></td>
								<td><button id="irbutton">Item Request</button></td>
								<td><button id="erbutton">Employee Request</button></td>
								<td><button id="srbutton">Supplier Request</button></td>
							</tr>
						</table>
						
						<div id="rf">
							<h1>Customer Request</h1>
							<table id="tblTRRF" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>
						<div id="ss">
							<h1>Shipping Setup</h1>
							<table id="tblTRSS" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>
						<div id="ir">
							<h1>Item Request</h1>
							<table id="tblTRIR" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>
						<div id="er">
							<h1>Employee Request</h1>
							<table id="tblTRER" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>
						<div id="sr">
							<h1>Supplier Request</h1>
							<table id="tblTRSR" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-xs-12">
					<div class="table-data-summary">
						<a href="list/tickets/msrf" class="link">MSRF Concerns</a>
						<select id="msrfStatus" class="status-dropdown">
							<option value="">All Status</option>
							<option>Open</option>
							<option>In Progress</option>
							<option>On Going</option>
							<option>Resolved</option>
							<option>Closed</option>
							<option>Rejected</option>
							<option>Returned</option>
						</select>
						<button id="filterMsrf" class="status-button">Filter</button>
						<table id="tblMsrf" class="table">
							<thead>
								<tr>
									<th>Ticket ID</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<td class="table-data">
						<div class="table-data-summary">
							<a href="list/tickets/tracc_concern" class="link">TRACC Concerns</a>
							<select id="concernStatus" class="status-dropdown">
								<option value="">All Status</option>
								<option>Open</option>
								<option>Done</option>
								<option>In Progress</option>
								<option>Rejected</option>
								<option>Resolved</option>
								<option>Closed</option>
							</select>
							<button id="filterConcern" class="status-button">Filter</button>
							<table id="tblConcerns" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
						</div>
					</td>
				</div>
				<div class="col-lg-6 col-xs-12">
					<div class="table-data-summary">
						<a href="list/tickets/tracc_request" class="link">TRACC Requests</a>
						<select id="requestStatus" class="status-dropdown">
							<option value="">All Status</option>
							<option>Open</option>
							<option>Approved</option>
							<option>In Progress</option>
							<option>Rejected</option>
							<option>Resolved</option>
							<option>Closed</option>
						</select>
						<button id="filterRequest" class="status-button">Filter</button>
						<table id="tblRequests" class="table">
							<thead>
								<tr>
									<th>Ticket ID</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</section>
    </div>
</div>

<script src="<?= base_url(); ?>/assets/dist/dist/js/external/jquery/jquery.js"></script>
<script type="text/javascript">
	$('#filterMsrf').click(function() {
		status = $('#msrfStatus').val();
		$('#tblMsrf').DataTable().ajax.reload();
	});

	$('#filterConcern').click(function() {
		status = $('#concernStatus').val();
		$('#tblConcerns').DataTable().ajax.reload();
	});

	$('#filterRequest').click(function() {
		status = $('#requestStatus').val();
		$('#tblRequests').DataTable().ajax.reload();
	})

	var serverTime = "<?= $current_time; ?>";
    var timeParts = serverTime.split(":");
    var currentHour = parseInt(timeParts[0], 10); // Get the current hour
    var currentMinute = parseInt(timeParts[1], 10); // Get the current minute

	// console.log(currentHour);
	// console.log(currentMinute);
	console.log(<?= $ticketopen->format("H.i"); ?> >= <?= $current_time; ?>);

	if (currentHour >= <?= $cutofftime->sub(new DateInterval('PT2H'))->format('H'); ?> && <?= $ticketopen->format("H.i"); ?> <= <?= $current_time; ?>) {
		$("#notificationBar").fadeIn();
	} else if (currentHour === 0) {
		$("#notificationBar").fadeOut();
	} 

	$("#dismissNotification").click(function () {
		$("#notificationBar").fadeOut();
	});

</script>