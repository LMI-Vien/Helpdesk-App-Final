<a href="<?= base_url('admin/dashboard')?>" class="logo">
	<span class="logo-mini"><b>L</b>MI</span>
    <span class="logo-lg"><b>ICT</b> Helpdesk</span>
</a>
<nav class="navbar navbar-static-top">
	<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
    	<ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-user"></span>
                    <span class="hidden-xs">
                        <!-- <?= $user_details['fullname']; ?> -->
                        <?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-footer">
                        <div class="pull-right">
                            <a href="<?= base_url(); ?>logout" id="logout" class="btn btn-danger btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
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