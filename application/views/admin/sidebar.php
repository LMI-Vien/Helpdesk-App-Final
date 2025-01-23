<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?= base_url(); ?>sys/admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <!-- Admin Tickets  -->
            <?php if ($this->session->userdata('login_data')['role'] !== 'L3'): ?>
            <li class="treeview <?= ($active_menu == 'admin_creation_ticket') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-ticket"></i> <span>My Tickets</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'admin_creation_msrf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/creation_tickets/msrf"><i class="fa fa-circle-o"></i> My MSRF Form List</a></li>
                    
                    <li class="<?= ($active_menu == 'admin_creation_ticket_tracc_concern') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/creation_tickets/tracc_concern"><i class="fa fa-circle-o"></i> My TRACC Concern List</a></li>
                    
                    <li class="<?= ($active_menu == 'admin_creation_ticket_tracc_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/creation_tickets/tracc_request"><i class="fa fa-circle-o"></i> My TRACC Request List</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('login_data')['role'] !== 'L3'): ?>
                <li class="treeview <?= ($active_menu == 'admin_creation_request_form') ? 'active' : ''; ?>">
                    <a href="#">
                        <i class="fa fa-ticket"></i> <span>Tracc Request Forms</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= ($active_menu == 'admin_customer_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/create/tickets/tracc_request/customer_request"><i class="fa fa-circle-o"></i> Customer Request Form</a></li>
                        <li class="<?= ($active_menu == 'admin_shipping_setup') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/create/tickets/tracc_request/shipping_setup"><i class="fa fa-circle-o"></i> Shipping Setup Form</a></li>
                        <li class="<?= ($active_menu == 'admin_employee_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/create/tickets/tracc_request/employee_request"><i class="fa fa-circle-o"></i> Employee Request Form</a></li>
                        <li class="<?= ($active_menu == 'admin_item_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/create/tickets/tracc_request/item_request"><i class="fa fa-circle-o"></i> Item Request Form</a></li>
                        <li class="<?= ($active_menu == 'admin_supplier_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/create/tickets/tracc_request/supplier_request"><i class="fa fa-circle-o"></i> Supplier Request Form</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="treeview <?= ($active_menu == 'system_tickets_list' || $active_menu == 'open_tickets' || $active_menu == 'other_menu' || $active_menu == 'admin_list_tickets' || $active_menu == 'admin_list_tracc_concern' || $active_menu == 'admin_list_tracc_request' || $active_menu == 'approved_tickets') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-ticket"></i> <span>Tickets Approval</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'admin_list_msrf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/msrf"><i class="fa fa-circle-o"></i> MSRF Form List <?= $unopenedMSRF > 0 ? "<span class='badge'>" . $unopenedMSRF . "</span>" : "" ?></a></li>
                    
                    <li class="<?= ($active_menu == 'admin_list_tracc_concern') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_concern"><i class="fa fa-circle-o"></i> TRACC Concern List <?= $unopenedTraccConcern > 0 ? "<span class='badge'>" . $unopenedTraccConcern . "</span>" : "" ?></a></li>
                    
                    <li class="<?= ($active_menu == 'admin_list_tracc_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_request"><i class="fa fa-circle-o"></i> TRACC Request List <?= $unopenedTraccRequest > 0 ? "<span class='badge'>" . $unopenedTraccRequest . "</span>" : "" ?></a></li>
                </ul>
            </li>

            <?php if ($this->session->userdata('login_data')['role'] !== 'L2'): ?>
            <li class="treeview <?= ($active_menu == 'closed_tickets_list') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-ticket"></i> <span>Closed Tickets</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'admin_list_msrf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/msrf_closed"><i class="fa fa-circle-o"></i> MSRF Form List</a></li>
                    
                    <li class="<?= ($active_menu == 'admin_list_tracc_concern') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_concerns_closed"><i class="fa fa-circle-o"></i> TRACC Concern List</a></li>
                    
                    <li class="<?= ($active_menu == 'admin_list_tracc_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_request_closed"><i class="fa fa-circle-o"></i> TRACC Request List</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <!-- Try -->
            <li class="treeview <?= ($active_menu == 'customer_request_form_pdf' || $active_menu == 'customer_shipping_setup_pdf' || $active_menu == 'employee_request_form_pdf' || $active_menu == 'item_request_form_pdf' || $active_menu == 'supplier_request_form_pdf') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa-solid fa-file-pdf" style="margin-right: 5px;"></i> <span> PDF Request Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'customer_request_form_pdf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/customer_request_form_pdf"><i class="fa fa-circle-o"></i> Customer Request Form</a></li>

                    <li class="<?= ($active_menu == 'customer_shipping_setup_pdf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/customer_shipping_setup_pdf"><i class="fa fa-circle-o"></i> Customer Shipping Setup</a></li>

                    <li class="<?= ($active_menu == 'employee_request_form_pdf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/employee_request_form_pdf"><i class="fa fa-circle-o"></i> Employee Request Form</a></li>

                    <li class="<?= ($active_menu == 'item_request_form_pdf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/item_request_form_pdf"><i class="fa fa-circle-o"></i> Item Request Form</a></li>

                    <li class="<?= ($active_menu == 'supplier_request_form_pdf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/supplier_request_form_pdf"><i class="fa fa-circle-o"></i> Supplier Request Form</a></li>            
                </ul>
            </li>
            <!-- Try -->

            <?php if ($this->session->userdata('login_data')['role'] !== 'L2'): ?>
            <li class="treeview <?= ($active_menu == 'system_administration' || $active_menu == 'users' || $active_menu == 'team' || $active_menu == 'cutoff') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-gear"></i> <span>System Administration</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'users') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/users"><i class="fa fa-users"></i> Users</a></li>
                    <li class="<?= ($active_menu == 'team') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/team"><i class="fa solid fa-user-plus"></i> Departments</a></li>
                    <li class="<?= ($active_menu == 'cutoff') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/cutoff"><i class="fa-solid fa-ban"></i>  Cutoff</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <li class="<?= ($active_menu == 'print') ? 'active' : ''; ?>">
                <a href="<?= base_url(); ?>sys/admin/print">
                    <i class="fa fa-print"></i> <span>Print Report</span>
                </a>
            </li>
        </ul>
	</section>
</aside>