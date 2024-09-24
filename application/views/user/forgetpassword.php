<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo $this->settings['app_name']; ?>:: <?php echo $title; ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= site_url() ?>img/indobypass_icon_new.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="<?= site_url() ?>assets/assets_members/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { "families": ["Public Sans:300,400,500,600,700"] },
            custom: { "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?= site_url() ?>assets/assets_members/css/fonts.min.css'] },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= site_url() ?>assets/assets_members/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= site_url() ?>assets/assets_members/css/plugins.min.css">
    <link rel="stylesheet" href="<?= site_url() ?>assets/assets_members/css/kaiadmin.min.css">
</head>
<body class="login">
    <div class="wrapper wrapper-login wrapper-login-full p-0">
        <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-secondary-gradient">
            <img src="<?= site_url() ?>img/indobypass_logo_new.png" width="60%" alt="Indobypass Logo" />
            <p class="subtitle text-white op-7">SEAMLESS BYPASS SOLUTION</p>
        </div>
        <div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
            <div class="container container-login container-transparent animated fadeIn">
                <h3 class="text-center">Forget Password</h3>
                <div class="login-form">
                    <?= $this->session->flashdata('message') ?>
                    <?= form_error('Email', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>

                    <?php echo form_open('forgot_password', array('role' => 'form', 'method' => 'post')); ?>
                    <div class="form-group">
                        <label for="Email"><b><?php echo $this->lang->line('forgot_password_lb_email') ?></b></label>
                        <input type="email" id="Email" name="Email" class="form-control" value="" placeholder="<?php echo $this->lang->line('forgot_password_lb_email') ?>" required>
                    </div>
                    <div class="form-group form-action-d-flex mb-3">
                        <button type="submit" class="btn btn-secondary col-md-5 float-end mt-3 mt-sm-0 fw-bold"><?php echo $this->lang->line('forgot_password_btn_submit') ?></button>
                    </div>
                    <?php echo form_close(); ?>

                    <div class="login-account">
                        <span class="msg">Already have an account?</span>
                        <a href="<?php echo site_url('login'); ?>" class="link">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= site_url() ?>assets/assets_members/js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= site_url() ?>assets/assets_members/js/core/popper.min.js"></script>
    <script src="<?= site_url() ?>assets/assets_members/js/core/bootstrap.min.js"></script>
    <script src="<?= site_url() ?>assets/assets_members/js/kaiadmin.min.js"></script>
</body>
</html>
