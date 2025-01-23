<style>

    .alert {
        margin-bottom: -20px;
    }

    .alert-content {
        background: #9a1b1e;
        color: white;
        padding: 5px;
        padding-top: 0.5px;
        padding-left: 20px;
        padding-right: 20px;
        border-radius: 4px;
        animation: animation 0.7s forwards;
    }

    @keyframes animation {
        20% {box-shadow: 0px 0px 2px 5px #9a1b1e}
        80% {box-shadow: 0px 0px 2px 5px #9a1b1e}
        100% {box-shadow: 0px 0px 0px 0px #9a1b1e}
    }

    .alert-content li {
        font-size: 18px;
    }

</style>

<div class="content-wrapper">
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
        <div class="alert">
            <?php
            if ($this->session->userdata('data')) {
                $data = $this->session->userdata('data');
                $current_user_id = $this->session->userdata('login_data')['user_id']; 
                $requestor_user_id = $data['recid']; 

                if ($current_user_id == $requestor_user_id) {
                    $count = 0;

                    foreach ($data['checkbox_data'] as $value) {
                        $count += $value;
                    }

                    // Display alert if there are forms to fill and session has not expired
                    if ($count > 0 && time() < $data['expires_at']) : ?>
                        <div class="alert-content" id="form-alert">
                            <h1>Please fill up the following forms:</h1>
                            <ul>
                                <?php 
                                foreach ($data['checkbox_data'] as $key => $checkbox_value) {
                                    if ($checkbox_value == 1) {
                                        echo '<li>' . htmlspecialchars($key) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    <?php endif;
                }
            }
            ?>
        </div>


        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="tblTraccRequest" class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ticket Number</th>
                                            <th>Requested By</th>
                                            <th>Subject</th>
                                            <th>Priority</th>
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