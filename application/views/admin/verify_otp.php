<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin OTP Verification</title>
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
<body>
    <div class="container" style="min-height: 100vh; display: flex; align-items: center;justify-content: center;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>OTP Verification (Admin)</h4>
                    </div>
                    <div class="card-body">
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
                        <p>Masukkan kode OTP yang telah dikirim ke email admin Anda.</p>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="otp_code">Kode OTP</label>
                                <input type="text" class="form-control" id="otp_code" name="otp_code" maxlength="6" pattern="[0-9]{6}" required placeholder="Enter 6-digit code">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Verifikasi OTP</button>
                            </div>
                        </form>
                        <div class="text-center">
                            <a href="javascript:void(0)" id="resend_otp" class="btn btn-link">Kirim ulang OTP</a>
                        </div>
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

<script>
$(document).ready(function() {
    $('#resend_otp').click(function() {
        $.ajax({
            url: '<?php echo site_url("admin/session/send_otp_admin"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                }
            }
        });
    });
    // Auto-focus on OTP input
    $('#otp_code').focus();
    // Only allow numbers
    $('#otp_code').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
