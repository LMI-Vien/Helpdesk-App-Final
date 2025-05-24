<nav class="navbar navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<a href="<?= base_url(); ?>sys/users/dashboard" class="navbar-brand"><b>ICT</b> Helpdesk</a>
          	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            	<i class="fa fa-bars"></i>
          	</button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        	<ul class="nav navbar-nav">
				<li class="">
					<a href="<?= base_url(); ?>sys/users/dashboard">Dashboard</a>
				</li>
				<?php if ($getdept[0]['dept_id'] == 1): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tickets Concerns <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/msrf">MSRF Concern List</a></li>
							<li><a href= "<?= base_url(); ?>sys/users/list/tickets/tracc_concern">TRACC Concern List</a></li>
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/tracc_request">TRACC Request List</a></li>
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/tracc_request">TR Forms List</a></li>
						</ul>
					</li>
				<?php else: ?>
					<li class="dropdown">
                    	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Creation Tickets<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/msrf">MSRF List Creation</a></li>
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/tracc_concern">TRACC Concern Creation</a></li>
							<li><a href="<?= base_url(); ?>sys/users/list/tickets/tracc_request">TRACC Request Creation</a></li>
							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">TR Forms Creation <span class="caret right-caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?= base_url(); ?>sys/users/create/tickets/trf_customer_request_form_tms">Customer Request Form TMS</a></li>
									<li><a href="<?= base_url(); ?>sys/users/create/tickets/trf_customer_shipping_setup">Customer Shipping Setup</a></li>
									<li><a href="<?= base_url(); ?>sys/users/create/tickets/trf_employee_request_form">Employee Request Form</a></li>
									<li><a href="<?= base_url(); ?>sys/users/create/tickets/trf_item_request_form">Item Request Form</a></li>
									<li><a href="<?= base_url(); ?>sys/users/create/tickets/trf_supplier_request_form_tms">Supplier Request Form TMS</a></li>
								</ul>
							</li>
						</ul>
                	</li>
				<?php endif; ?>
        	</ul>
        </div>
        <div class="navbar-custom-menu">
        	<ul class="nav navbar-nav">
        		<li class="dropdown user user-menu">
        			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
        				<span class="glyphicon glyphicon-user"></span>
        				<span class="hidden-xs"><?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?></span>
        			</a>
        			<ul class="dropdown-menu">
                		<li class="user-footer">
                  			<div class="pull-right">
                    			<a href="<?= site_url('Main/logout'); ?>" id="logout" class="btn btn-default btn-flat">Sign out</a>
                  			</div>
                		</li>
        			</ul>
        		</li>
        	</ul>
        </div>
	</div>
</nav>

<script>
	$("#logout").on("click", function(e) {
		e.preventDefault();

		var logoutUrl = $(this).attr("href");

		Swal.fire({
			title: "Are you sure?",
			text: "You will be logged out of your session.",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: "Yes, log me out!",
			cancelButtonText: "Cancel",
			customClass: {
				popup: 'swal-wide' 
			},
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = logoutUrl;
			}
		});
	});
</script>