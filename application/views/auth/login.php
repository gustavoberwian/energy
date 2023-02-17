<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
        <meta http-equiv="Content-Language" content="pt-BR">

		<title>Easymeter</title>
		<meta name="keywords" content="Easymeter" />
		<meta name="description" content="Easymeter - Controle e Economia">
		<meta name="author" content="www.easymeter.com.br">

		<!-- Favicon -->
		<link rel="shortcut icon" href="<?= base_url('favicon.png'); ?>" type="image/x-icon" />

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('vendor/animate/animate.compat.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('vendor/font-awesome/css/all.min.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('vendor/boxicons/css/boxicons.min.css'); ?>">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?= base_url('assets/css/skin.css'); ?>" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>" />

		<!-- Head Libs -->
		<script src="<?= base_url('vendor/modernizr/modernizr.js'); ?>"></script>
	</head>

	<body>
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo float-start">
					<img src="<?= base_url('assets/img/logo.png'); ?>" height="54" alt="Easymeter" />
				</a>

				<div class="panel card-sign">
					<div class="card-title-sign mt-3 text-end">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> Entrar</h2>
					</div>
					<div class="card-body">
                        <?php if (isset($message)) : ?>
                            <div class="alert alert-danger">
                                <ul class="login-message mb-0">
                                    <?= $message;?>
                                </ul>
                            </div>
						<?php endif; ?>
                        <?php if (isset($_SESSION['message'])) : ?>
                            <div class="alert alert-danger">
                                <ul class="login-message mb-0">
                                    <?= $_SESSION['message']?>
                                </ul>
                            </div>							
                        <?php endif; ?>
						<form action="<?= site_url('auth/login'); ?>" method="post">
							<div class="form-group mb-3">
								<label>Email</label>
								<div class="input-group">
									<input name="identity" type="email" class="form-control form-control-lg" data-msg-required="Campo Obrigatório" required />
									<span class="input-group-text">
										<i class="bx bx-user text-4"></i>
									</span>
								</div>
							</div>

							<div class="form-group mb-3">
								<div class="clearfix">
									<label class="float-start">Senha</label>
									<a href="<?= site_url('auth/recover'); ?>" class="float-end">Esqueceu a senha?</a>
								</div>
								<div class="input-group">
									<input name="password" type="password" class="form-control form-control-lg" data-msg-required="Campo Obrigatório" required />
									<span class="input-group-text">
										<i class="bx bx-lock text-4"></i>
									</span>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Lembrar</label>
									</div>
								</div>
								<div class="col-sm-4 text-end">
									<button type="submit" class="btn btn-primary mt-2">Entrar</button>
								</div>
							</div>

							<p class="text-center">Não possui sua conta ainda? <a href="<?= site_url('auth/signup'); ?>">Crie aqui!</a></p>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017-<?= date("Y"); ?>. Todos os direitos reservados.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="<?= base_url('vendor/jquery/jquery.js'); ?>"></script>
		<script src="<?= base_url('vendor/jquery-browser-mobile/jquery.browser.mobile.js'); ?>"></script>
		<script src="<?= base_url('vendor/popper/umd/popper.min.js'); ?>"></script>
		<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
		<script src="<?= base_url('vendor/common/common.js'); ?>"></script>
		<script src="<?= base_url('vendor/nanoscroller/nanoscroller.js'); ?>"></script>
        <script src="<?= base_url('vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>

		<!-- Page Specific -->
		<script src="<?= base_url('assets/js/pages/auth/login.js'); ?>"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?= base_url('assets/js/theme.js'); ?>"></script>

		<!-- Theme Custom -->
		<script src="<?= base_url('assets/js/custom.js'); ?>"></script>

		<!-- Theme Initialization Files -->
		<script src="<?= base_url('assets/js/theme.init.js'); ?>"></script>

	</body>
</html>