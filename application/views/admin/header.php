<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ICT Helpdesk Application</title>
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<link rel="icon" href="<?= base_url(); ?>assets/images/ict-logo-png.png" type="image/png">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/Ionicons/css/ionicons.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/dist/css/AdminLTE.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/dist/css/skins/_all-skins.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/morris.js/morris.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/jvectormap/jquery-jvectormap.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/iCheck/all.css">
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/admin_dashboard.css">
	<!-- Font Awesome CDN -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<script src="<?= base_url(); ?>/assets/toast/jqm.js"></script>
	<script src="<?= base_url(); ?>/assets/toast/toast.js"></script>

	<!-- SweetAlert CSS -->
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/sweetalert2.min.css">

	<!-- SweetAlert JS -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> -->
	<script src="<?= base_url(); ?>/assets/dist/dist/js/sweetalert2.all.min.js"></script>


	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

	<!-- JQuery Tabs && JQuery UI  -->
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/jquery-ui-1.14.1/jquery-ui.css">
	<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css"> -->
	<!-- <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script> -->
	<script src="<?= base_url(); ?>/assets/dist/dist/js/external/jquery/jquery.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/dist/js/jquery-ui.js"></script>
	<script src="<?= base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>

	<!-- for sweetalert in JQuery Tabs -->
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/dist/css/custom-popup.css">
</head>
<body class="hold-transition skin-red sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
			<?php $this->load->view('admin/navbar'); ?>
		</header>