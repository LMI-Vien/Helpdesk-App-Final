<?php 
    $role = $this->session->userdata('login_data')['role']; 
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control Panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>       
    </section>
    <section class="content">
        <div class="row">
            <!-- Total Tickets MSRF -->
            <a href="<?= base_url(); ?>sys/admin/list/ticket/msrf" class="small-box-link">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-light-blue">
                        <div class="inner">
                            <h3><?= $total_msrf_tickets ?></h3> <!-- Replace with dynamic count -->
                            <p>MSRF Total Tickets</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>
            </a>
            <!-- Total Tickets TRACC CONCERN -->
            <a href="<?= base_url(); ?>sys/admin/list/ticket/msrf" class="small-box-link">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-olive">
                        <div class="inner">
                            <h3><?= $total_tracc_concern_tickets ?></h3> <!-- Replace with dynamic count -->
                            <p>TRACC Concern Total Tickets</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>
            </a>
            <!-- Total Tickets TRACC REQUEST -->
            <a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_concern" class="small-box-link">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3><?= $total_tracc_request_tickets ?></h3> <!-- Replace with dynamic count -->
                            <p>TRACC Request Total Tickets</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>
            </a>
            <!-- Total Users -->
            <a href="<?= $role !== 'L2' ? base_url() . 'sys/admin/users' : '#' ?>" class="small-box-link" <?= $role === 'L2' ? 'id="restricted-link"' : '' ?>>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= $total_users ?></h3> <!-- Replace with dynamic count -->
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <span class="small-box-footer"><?= $role === 'L2' ? 'No Access <i class="fa fa-ban"></i>' : 'More info <i class="fa fa-arrow-circle-right"></i>' ?></span>
                    </div>
                </div>
            </a>
            <!-- Total Departments -->
            <a href="<?= $role !== 'L2' ? base_url() . 'sys/admin/team' : '#' ?>" class="small-box-link" <?= $role === 'L2' ? 'id="restricted-departments-link"' : '' ?>>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?= $total_departments ?></h3> <!-- Replace with dynamic count -->
                            <p>Total Departments</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-building"></i>
                        </div>
                        <span class="small-box-footer"><?= $role === 'L2' ? 'No Access <i class="fa fa-ban"></i>' : 'More info <i class="fa fa-arrow-circle-right"></i>' ?></span>
                    </div>
                </div>
            </a>
        </div>
    </section>


    <!-- Alert for upload size -->
    <?php if ($this->session->flashdata('upload_alert')): ?>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-circle"></i> <?= $this->session->flashdata('upload_alert'); ?>
        </div>
    <?php endif; ?> 
           
</div>