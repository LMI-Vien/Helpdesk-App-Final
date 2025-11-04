<?php 
	$login = $this->session->userdata('login_data');
	$isL3  = isset($login['role']) && $login['role'] === 'L3';
?>

<div class="content-wrapper">
    <section class="content-header">
		<h1>
			List of Tickets (TRACC CONCERN)
			<small>Control Panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Tickets</li>
		</ol>
	</section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
						<div class="table-responsive">
							<table id="tblTicketsTraccConcern" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Control Number</th>
										<th>Date Reported</th>
										<th>Reported By</th>
										<th>Priority</th>
										<th>Company</th>
										<th>Status</th>
										<th>Dept. Head Approval Status</th>
										<th>ICT Approval Status</th>
										<?php if ($isL3): ?>
											<th>ICT Assigned</th>
										<?php endif; ?>
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>