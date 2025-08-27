<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Login - Admin Panel</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="MobileOptimized" content="320">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('assets_url');?>plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo $this->config->item('assets_url');?>css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo $this->config->item('assets_url');?>css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('assets_url');?>css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body class="login" style="background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%); min-height:100vh;">
	<div class="wrapper wrapper-login wrapper-login-full p-0">
		<div class="login-aside w-100 d-flex align-items-center justify-content-center bg-white">
			<div class="container container-login container-transparent animated fadeIn" style="max-width:400px;">
				<h3 class="text-center mb-4">Admin Login</h3>
				<div class="login-form">
					<?php if($this->session->flashdata('error')): ?>
						<div class="alert alert-danger">
							<?php echo $this->session->flashdata('error'); ?>
						</div>
					<?php endif; ?>
					<?php if($this->session->flashdata('success')): ?>
						<div class="alert alert-success">
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php endif; ?>
					<?php echo form_open('admin/session/login', array('role' => 'form', 'method' => 'post', 'class' => 'mb-3')); ?>
						<input type="hidden" name="return_url" value="<?php echo $this->input->get('return_url') ?>" />
						<div class="form-group mb-3">
							<label for="Email"><b>Email</b></label>
							<input type="email" id="Email" name="Email" class="form-control" value="<?php echo set_value('Email'); ?>" placeholder="Email" required>
						</div>
						<div class="form-group mb-3">
							<label for="Password"><b>Password</b></label>
							<a href="javascript:;" id="show-forgot" class="link float-end">Forgot password?</a>
							<div class="position-relative">
								<input type="password" id="Password" name="Password" class="form-control" placeholder="Password" required>
								<div class="show-password" style="position:absolute;top:8px;right:12px;cursor:pointer;">
									<i class="fa fa-eye"></i>
								</div>
							</div>
						</div>
						<div class="form-group mb-3 text-center">
							<!-- Google reCAPTCHA widget -->
							<div class="g-recaptcha d-inline-block" data-sitekey="6LdWw7QrAAAAAG-rmD7LKHSrLwfkiR-pwHU7XyS7"></div>
						</div>
						<div class="d-grid mb-2">
							<button type="submit" class="btn btn-primary fw-bold">Login</button>
						</div>
					<?php echo form_close(); ?>
					<div id="forgot-form" style="display:none; margin-top:20px;">
						<?php echo form_open('admin/session/forgot_password', array('class' => 'forget-form')) ?>
							<h5>Forgot Password?</h5>
							<p>Enter your e-mail address below to reset your password.</p>
							<div class="form-group mb-3">
								<input class="form-control" type="email" autocomplete="off" placeholder="Your email" name="Email" value="<?php echo set_value('Email') ?>" required />
							</div>
							<div class="d-grid gap-2">
								<button type="button" id="back-btn" class="btn btn-secondary">Back</button>
								<button type="submit" class="btn btn-info">Submit</button>
							</div>
						<?php echo form_close(); ?>
					</div>
				</div>
				<div class="text-center mt-4">
					<small>2025 &copy; INDOBYPASS.</small>
				</div>
			</div>
		</div>
	</div>
	<script src="<?= site_url() ?>assets/assets_members/js/core/jquery-3.7.1.min.js"></script>
	<script src="<?= site_url() ?>assets/assets_members/js/core/bootstrap.min.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
	$(document).ready(function() {
		$('#show-forgot').click(function(e) {
			e.preventDefault();
			$('.login-form').hide();
			$('#forgot-form').show();
		});
		$('#back-btn').click(function() {
			$('#forgot-form').hide();
			$('.login-form').show();
		});
		// Show/hide password
		$('.show-password').on('click', function() {
			var input = $('#Password');
			var icon = $(this).find('i');
			if (input.attr('type') === 'password') {
				input.attr('type', 'text');
				icon.removeClass('fa-eye').addClass('fa-eye-slash');
			} else {
				input.attr('type', 'password');
				icon.removeClass('fa-eye-slash').addClass('fa-eye');
			}
		});
	});
	</script>
</body>
</body>
<!-- END BODY -->
</html>