<a href="<?= base_url('sys/admin/dashboard')?>" class="logo">
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
                            <a href="<?= base_url(); ?>sys/logout" class="btn btn-danger btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>