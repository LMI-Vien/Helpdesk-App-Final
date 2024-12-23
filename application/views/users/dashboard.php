<style>

	.content {
		margin-bottom: 80px;
	}

	.col-lg-6 {
		margin: -20px;
		margin-top: 30px;
	}

	.col-xs-6 {
		padding: 0px;
		padding-right: 20px;
	}

	.col-lg-4 {
		margin-top: 10px;
	}

	.table-data {
		padding: 5px;
		vertical-align: top;
	}

	.summary-table {
		width: 100%;
		height: 420px;
		padding: 0px;
	}

	.summary {
		padding: 5px;
	}

	.summary-data {
		background-color: white;
		height: 100%;
		width: 100%;
		padding: 10px;
		border-radius: 5px;
		transition: 0.5s;
	}

	.summary-data:hover {
		box-shadow: 0px 0px 5px lightgray;
		transition: 0.3s;
	}

	.summary-data-table {
		width: 100%;
	}

	.table-data-summary {
		background-color: white;
		padding: 20px;
		height: 420px;
		border-radius: 5px;
		transition: 0.5s;
	}

	.table-data-summary:hover {
		box-shadow: 0px 0px 5px lightgray;
		transition: 0.3s;
	}


	.table-data tr:hover {
		background-color: #ecf0f5;
	}

	.status-dropdown {
		padding: 5px;
		border-radius: 5px;
		border-color: lightgray;
		font-size: 11px;
	}

	.status-button {
		padding: 5px;
		width: 70px;
		border-radius: 5px;
		border-width: 0;
		background-color: #337ab7;
		color: white;
		font-size: 11px;
	}

	.links {
		background-color: white;
		height: 100%;
		width: 100%;
		text-align: center;
		padding-top: 5px;
		padding-bottom: 21px;
		border-radius: 5px;
		transition: 0.5s;
	}

	.links:hover {
		box-shadow: 0px 0px 5px lightgray;
		transition: 0.3s;
	}

	.link-text {
		text-decoration: underline;
	}

	.link {
		display: block;
		margin-bottom: 15px;
		font-size: 25px;
		text-align: center;
		padding: 10px;
		border-radius: 10px;
		border: 1px solid #72afd2;
		transition: 0.2s;
	}

	.link:hover {
		background-color: #72afd2;
		color: white;
	}

	.col-lg-6 {
		margin-left: 0px;
	}

</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
            <h1>
          		Dashboard
          		<small>Control Panel</small>
        	</h1>
			<ol class="breadcrumb">
			    <li><a href="<?= base_url(); ?>sys/users/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="<?= base_url(); ?>sys/users/dashboard">Dashboard</a></li>
			</ol>
        </section>
		<section class="content">
			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<div class="links">
						<h3>Hello, <?= $user_details['fname']; ?>!</h3>
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
						<a class="link">Customer Request Form Status</a>
						<?php
							if($this->session->flashdata('editTR')) {
								echo "<p><b>";
								echo $this->session->flashdata('editTR');
								echo "</b></p>";
							}
						?>
						<table id="tblTR" class="table">
							<thead>
								<tr>
									<th>Ticket ID</th>
									<th>Remarks</th>
								</tr>
							</thead>
						</table>
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