<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ICT Helpdesk Application</title>
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" href="<?= base_url(); ?>assets/images/ict-logo-png.png" type="image/png">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/css/bootstrap.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/users.css">
	<!-- Font Awesome CDN -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

	<!-- SweetAlert CSS -->
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/dist/css/sweetalert2.min.css">

	<!-- SweetAlert JS -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> -->
	<script src="<?= base_url(); ?>/assets/dist/dist/js/sweetalert2.all.min.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/dist/js/external/jquery/jquery.js"></script>


</head>
<script>
	base_url = '<?= base_url();?>';
</script>
<body class="hold-transition skin-yellow layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <?php $this->load->view('users/navbar'); ?>
        </header>