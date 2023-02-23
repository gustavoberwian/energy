<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!doctype html>
<html class="<?= !in_array($method, array('profile')) ? 'dark' : ''; ?>" lang="">

<head>
    <!-- Basic -->
    <meta charset="UTF-8">

    <title>Easymeter</title>
    <meta name="keywords" content="Easymeter" />
    <meta name="description" content="Easymeter - Controle e Economia">
    <meta name="author" content="www.easymeter.com.br">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('favicon.png'); ?>" type="image/x-icon" />

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo base_url("vendor/bootstrap/css/bootstrap.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/animate/animate.compat.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("vendor/fontawesome/css/all.min.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/boxicons/css/boxicons.min.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/magnific-popup/magnific-popup.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/jquery-ui/jquery-ui.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/jquery-ui/jquery-ui.theme.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/bootstrap-multiselect/css/bootstrap-multiselect.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/morris/morris.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("vendor/daterangepicker/daterangepicker.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url('vendor/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('vendor/apexcharts/dist/apexcharts.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('vendor/pnotify/pnotify.custom.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('vendor/bootstrap-multiselect/css/bootstrap-multiselect.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('vendor/owl_carousel/owl.carousel.css'); ?>" />

    <?php if (in_array($method, array('profile'))) : ?>
        <link rel="stylesheet" href="<?php echo base_url('vendor/croppie/croppie.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('vendor/bootstrap-fileupload/bootstrap-fileupload.min.css'); ?>" />
    <?php endif; ?>

    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?php echo base_url("assets/css/theme.css"); ?>" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="<?php echo base_url("assets/css/skins/default.css"); ?>" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url("assets/css/custom.css"); ?>">

    <link rel="stylesheet" href="<?php echo base_url('vendor/select2/css/select2.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('vendor/select2-bootstrap-theme/select2-bootstrap.min.css'); ?>" />

    <!-- Head Libs -->
    <script src="<?php echo base_url("vendor/modernizr/modernizr.js"); ?>"></script>

</head>

<body>

<div class="preloader">
    <div class="speeding-wheel"></div>
</div>

<section class="body">

    <!-- end: header -->

    <div class="inner-wrapper pt-0">