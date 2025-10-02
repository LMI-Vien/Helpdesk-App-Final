<?php 
    $sess_login_data = $this->session->userdata('login_data');
    $role = $sess_login_data['role'];
    $department_id = $sess_login_data['dept_id'];
    $disabled = "";
    $readonly = "";
    $btn_label = "Submit Ticket";
    if ($role === "L1") {
        $department_head_status = $msrf['approval_status'];
        
        $status_msrf = $msrf['status'];

        if(($status_msrf === "In Progress" || $status_msrf === 'Approved' || $status_msrf === 'Closed' || $status_msrf === 'Rejected')) {
            $disabled = "disabled";
            $readonly = "readonly";
            $btn_label = "Update Ticket";
        } else {
            $disabled = "";
            $readonly = "";
        }
    }
    // if($role === "L1" && $department_id === "1"){
    //     $department_status = $msrf['approval_status'];
    //         if($department_status === "Rejected" || $department_status === "Returned", || $department_status === "Approved"){
    //             $disabled = "disabled";
    //         }
    // }else{
    //     $disabled = "";
    // }
    
?>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				MSRF Details
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">MSRF Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Ticket for MSRF</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('UsersMSRF_controller/update_status_msrf_assign'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>MSRF#</label>
			                    					<input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?= $msrf['ticket_id']; ?>" readonly>
			                    				</div>
			                    			</div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Requestor</label>
			                                        <input type="text" name="name" value="<?= htmlentities($msrf['requestor_name']); ?>" class="form-control select2" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Department</label>
			                                        <input type="text" name="department_description" id="department_description" value="<?= htmlentities($msrf['department']); ?>" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                    </div>
			                                </div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Date Requested</label>
			                                        <input type="date" name="date_req" id="date_req" class="form-control select2" value="<?= $msrf['date_requested']; ?>" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_need" class="form-control select2" value="<?= $msrf['date_needed']; ?>" style="width: 100%;" 
                                                    <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected'); ?> <?=$readonly?> min="<?= date('Y-m-d'); ?>">
			                                    </div>
			                                </div>

                                            <!-- ASSET CODE START -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Asset Code</label>
                                                    <input type="text" name="asset_code" class="form-control select2" value="<?php echo $msrf['asset_code']; ?>" style="width: 100%;" placeholder="Asset Code"
                                                    <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected'); ?> <?=$readonly?>>
                                                </div>
                                            </div>
                                            <!-- ASSET CODE END -->

                                            <!-- REQUEST CATEGORY START -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Request Category</label>
                                                    <select class="form-control select2" name="category" id="category" style="width: 100%;"
                                                    <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected'); ?><?=$disabled?>>
                                                        <option value="" disabled selected>Select Category</option>
                                                        <option value="computer"<?php if ($msrf['category'] == 'computer') echo ' selected'; ?>>Computer (Laptop or Desktop)</option>
                                                        <option value="printer"<?php if ($msrf['category'] == 'printer') echo ' selected'; ?>>Printer Concerns</option>
                                                        <option value="network"<?php if ($msrf['category'] == 'network') echo ' selected'; ?>>Network or Internet connection</option>
                                                        <option value="projector"<?php if ($msrf['category'] == 'projector') echo ' selected'; ?>>Projector / TV Set-up</option>
                                                        <option value="others"<?php if ($msrf['category'] == 'others') echo ' selected'; ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>                                          
                                            <!-- REQUEST CATEGORY END -->

                                            <!-- SPECIFY START -->
                                            <div class="col-md-12" id="specify-container" style="<?php echo ($msrf['specify'] == 'Others') ? '' : 'display: none;'; ?>">
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <input type="text" name="msrf_specify" id="msrf_specify" class="form-control" value="<?= $msrf['specify']; ?>" 
                                                    <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected'); ?>  <?=$readonly?>>
                                                </div>
                                            </div>
                                            <!-- SPECIFY END -->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>                                            
                                                    <textarea class="form-control" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"<?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected'); ?> <?=$readonly?>><?= $msrf['details_concern']; ?></textarea>
                                                </div>
                                            </div>

                                            <!-- New Section for File Display -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>View File</label><br>
                                                        <?php if (!empty($msrf['file'])): ?>
                                                            <a href="<?= site_url('uploads/msrf/' . $msrf['file']); ?>" target="_blank" class="btn btn-primary">
                                                                <i class="fa fa-eye"></i> View Uploaded File
                                                            </a>
                                                        <?php else: ?>
                                                        <div class="alert alert-light" role="alert">
                                                            <i class="fa fa-exclamation-circle"></i> No file uploaded.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div> 

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="approval_stat" id="approval_stat" style="width: 100%;" <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected') echo 'disabled'; ?>  disabled>
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($msrf['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Returned"<?php if ($msrf['approval_status'] == 'Returned') echo ' selected'; ?>>Returned</option>
                                                    </select>
                                                </div>
                                            </div>

                                        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>ICT Approval Status</label>
                                                    <select name="it_approval_stat" id="it_approval_stat" class="form-control select2" disabled>
                                                        <option value=""disabled selected></option>
                                                        <option value="Approved"<?php if ($msrf['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Returned"<?php if ($msrf['it_approval_status'] == 'Returned') echo ' selected'; ?>>Returned</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="ictassign" style="display:none;">
                                                <div class="form-group">
                                                    <label>ICT Assign To</label>
                                                    <select name="assign_to" id="assign_to" class="form-control select2" <?= $disabled ?>>
                                                        <?php foreach($ict_dept as $ict): ?>
                                                            <option value="<?= $ict['full_name']; ?>" <?= $ict['full_name'] == $msrf['assigned_it_staff'] ? 'selected' : ''; ?>><?= $ict['full_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- REASON WHY REJECTED in db remarks_ict -->
                                            <div class="col-md-12" id="reason">
                                                <div class="form-group">
                                                    <label>Reason for Reject Tickets</label>
                                                    <textarea class="form-control" name="rejecttix" id="rejecttix" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" readonly><?= $msrf['remarks_ict']; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- REASON WHY REJECTED in db remarks_ict -->
                                          
                                            <div class="col-md-12" id="returnedReason" style="display: none;">
                                                <div class="form-group">
                                                    <label>Reason for Returned Tickets</label>
                                                    <textarea class="form-control" name="returnedReason" id="returnedReason" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; text-align: left; resize: vertical;" readonly><?= isset($msrf['returned_ticket_reason']) ? htmlspecialchars($msrf['returned_ticket_reason']) : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <!-- style="display:none;" -->
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary" <?=$disabled?>><?=$btn_label?></button>
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

<script>
    $(document).ready(function() {
		$("#reason").hide();

		function toggleReasonField() {
			var itApprovalStatus = $('#it_approval_stat').val();
			var approvalStatus = $('#approval_stat').val();

			if (itApprovalStatus === 'Rejected' || approvalStatus === 'Rejected') {
				$("#reason").show();  
			} else {
				$("#reason").hide();  
			}
		}

        function toggleReturnedReasonField() {
			var manApprovalStatus = $('#approval_stat').val();
			console.log(manApprovalStatus);

			if (manApprovalStatus === 'Returned') {
				$("#returnedReason").show(); 
			} else {
				$("#returnedReason").hide();
			}
		}

		$('#it_approval_stat, #approval_stat').on('change', function() {
			toggleReasonField();
		});

        $('#approval_stat').on('change', function() {
			toggleReturnedReasonField();
		});

		toggleReasonField();
        toggleReturnedReasonField();

        if ($('#it_approval_stat').val() == 'Approved') {
			$('#ictassign').show();
		}

		$('#it_approval_stat').on('change', function() {
			var selectedValue = $(this).val();

			if (selectedValue == 'Approved') {
				$('#ictassign').show();  
			} else {
				$('#ictassign').hide();  
			}
		});

        if($('#category').val() == 'others') {
            $('#specify-container').show();
        } else {
            $('#specify-container').hide();
        }

        $('#category').change(function() {
            if ($(this).val() == 'others') {
                $('#specify-container').show();
            } else {
                $('#specify-container').hide();
            }
        });
	});
</script>