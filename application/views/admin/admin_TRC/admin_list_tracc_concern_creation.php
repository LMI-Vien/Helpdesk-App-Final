<div class="content-wrapper">
    <?php if ($this->session->flashdata('checkbox_data')): ?>
        <p><?= $this->session->flashdata('checkbox_data'); ?></p>
    <?php endif; ?>
    <div class="container">
        <section class="content-header">
            <h1>
				Ticket Creation
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
                <li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">List of Tickets</li>
			</ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="tblTraccConcernAdmin" class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Control Number</th>
                                            <th>Reported By</th>
                                            <th>Date Reported</th>
                                            <th>Priority</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                            <th>Dept. Head Approval Status</th>
                                            <th>ICT Approval Status</th>
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
</div>