<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo $this->settings['app_name']; ?>:: Register</title>
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
        <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-primary-gradient">
            <img src="<?= site_url() ?>img/indobypass_logo_new.png" width="60%" alt="Indobypass Logo" />
            <p class="subtitle text-white op-7">SEAMLESS BYPASS SOLUTION</p>
        </div>
        <div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
            <div class="container container-login container-transparent animated fadeIn">
                <h3 class="text-center">Register</h3>
                <div class="register-form">
                    <?= form_error('FirstName', '<div class="alert alert-danger">', '</div>'); ?>
                    <?= form_error('LastName', '<div class="alert alert-danger">', '</div>'); ?>
                    <?= form_error('Email', '<div class="alert alert-danger">', '</div>'); ?>
                    <?= form_error('Password', '<div class="alert alert-danger">', '</div>'); ?>
                    <?= form_error('CPassword', '<div class="alert alert-danger">', '</div>'); ?>
                    
                    <?php echo form_open('register', ['role' => 'form', 'method' => 'post']); ?>
                        <div class="form-group mb-3">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="FirstName" value="<?= set_value('FirstName'); ?>" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="LastName" value="<?= set_value('LastName'); ?>" class="form-control" placeholder="Last Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Email">Email</label>
                            <input type="email" name="Email" value="<?= set_value('Email'); ?>" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Password">Password</label>
                            <input type="password" name="Password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="CPassword">Confirm Password</label>
                            <input type="password" name="CPassword" class="form-control" placeholder="Confirm Password" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary col-12 fw-bold">Register</button>
                        </div>
                    <?php echo form_close(); ?>

                    <div class="login-account text-center mt-3">
                        <span class="msg">Already have an account?</span>
                        <a href="<?= site_url('login'); ?>" class="link">Login</a>
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
