<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">Profile</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Profile</a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
        <?= $this->session->flashdata('message') ?>
        <?= form_error('FirstName', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <?= form_error('LastName', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <?= form_error('Email', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <?= form_error('CurrentPassword', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">My Profile</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php echo form_open('member/dashboard/editprofile', array('role' => 'form', 'method' => 'post','id' => 'imeireq' ,'name' => 'form2', 'class' => 'form-horizontal', 'onsubmit' => 'openLoading()')); ?>
                    <?php echo form_hidden("ID", $data[0]['ID']); ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('my_account_lb_first_name') ?></label>
                        <div class="col-8">
                            <input type="text" name="FirstName"
                                placeholder="<?php echo $this->lang->line('my_account_lb_first_name') ?>"
                                value="<?php echo $data[0]['FirstName']; ?>" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('my_account_lb_last_name') ?></label>
                        <div class="col-8">
                            <input type="text" name="LastName"
                                placeholder="<?php echo $this->lang->line('my_account_lb_last_name') ?>"
                                value="<?php echo $data[0]['LastName']; ?>" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('my_account_lb_email') ?></label>
                        <div class="col-8">
                            <input type="email" name="Email" readonly="readonly"
                                placeholder="<?php echo $this->lang->line('my_account_lb_email') ?>"
                                value="<?php echo $data[0]['Email']; ?>" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label
                            class="control-label"><?php echo $this->lang->line('my_account_lb_current_password') ?></label>
                        <div class="col-8">
                            <input type="password" name="CurrentPassword"
                                placeholder="<?php echo $this->lang->line('my_account_lb_current_password') ?>" required
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label
                            class="control-label"><?php echo $this->lang->line('my_account_lb_new_password') ?></label>
                        <div class="col-8">
                            <input type="password" name="NewPassword"
                                placeholder="<?php echo $this->lang->line('my_account_lb_new_password') ?>"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label
                            class="control-label"><?php echo $this->lang->line('my_account_lb_confirm_new_password') ?></label>
                        <div class="col-8">
                            <input type="password" name="ConfirmPassword"
                                placeholder="<?php echo $this->lang->line('my_account_lb_confirm_new_password') ?>"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label"><?php echo $this->lang->line('my_account_api_key') ?></label>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <input type="text" placeholder="<?php echo $this->lang->line('my_account_api_key') ?>"
                                value="<?php echo set_value('ApiKey', $data[0]['ApiKey']); ?>" class="form-control"
                                disabled="disabled">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pt-xl-2 pt-lg-2 pt-md-0 pt-sm-0">
                            <div class="d-flex">
                                <input type="checkbox" name="ResetApiKey" value="reset">
                                <div>&nbsp;Reset API Key</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label"><?php echo $this->lang->line('my_account_server_ip') ?></label>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <input type="text" placeholder="<?php echo $this->lang->line('my_account_server_ip') ?>"
                                value="<?php echo set_value('ServerIP', $data[0]['ServerIP']); ?>" class="form-control"
                                disabled="disabled">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pt-xl-2 pt-lg-2 pt-md-0 pt-sm-0">
                            <div class="d-flex">
                                <input type="checkbox" name="ResetServerIP" value="reset">
                                <div>&nbsp;Reset IP Bindings</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('my_account_api_status') ?></label>
                        <div class="col-8">
                            <?php echo form_dropdown('ApiStatus', ['Enabled'=>'Enabled', 'Disabled'=>'Disabled'], set_value('ApiStatus', $data[0]['ApiStatus']), 'class="form-control"'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-12 text-center">
                            <button type="submit"
                                class="btn btn-primary btn-sm"><?php echo $this->lang->line('my_account_btn_update') ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12">
                <div class="card card-profile">
                    <div class="card-header" style="height: 120px">
                        <div class="profile-picture position-relative">
                            <div class="avatar avatar-xl mb-2">
                                <?php
                                    $profileImg = isset($this->session) && $this->session->userdata('ProfileImage')
                                        ? base_url('assets/assets_members/img/profile_upload/' . $this->session->userdata('ProfileImage'))
                                        : base_url('assets/assets_members/img/profile.jpg');
                                ?>
                                <img id="profilePreview" src="<?= $profileImg ?>" alt="Profile Image" class="avatar-img rounded-circle" style="object-fit:cover; width:100px; height:100px;" />
                            </div>
                            <!-- Form upload foto profile dipindah ke bawah tombol View Full Profile -->
                        </div>
                    </div>
</script>
<script>
// Preview image sebelum upload, tampilkan nama file, dan tombol Save
document.getElementById('profileImageInput')?.addEventListener('change', function(e) {
    var fileInput = e.target;
    var fileNameDiv = document.getElementById('selectedFileName');
    var saveBtn = document.getElementById('saveProfileImageBtn');
    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('profilePreview').src = ev.target.result;
        }
        reader.readAsDataURL(fileInput.files[0]);
        // Show filename and Save button
        fileNameDiv.innerHTML = '<i class="fa fa-file"></i> ' + fileInput.files[0].name;
        fileNameDiv.style.display = 'block';
        saveBtn.style.display = 'block';
    } else {
        fileNameDiv.innerHTML = '';
        fileNameDiv.style.display = 'none';
        saveBtn.style.display = 'none';
    }
});
</script>
                    <div class="card-body">
                        <div class="user-profile text-center">
                            <div class="name">
                                <?= $this->session->userdata('MemberFirstName') . " " . $this->session->userdata("MemberLastName"); ?>
                            </div>
                            <div class="job"><?php echo $this->session->userdata("MemberEmail"); ?></div>
                            <div class="desc">I knew that you would do this!</div>
                            <div class="social-media">
                                <a class="btn btn-info btn-twitter btn-sm btn-link" href="#">
                                    <span class="btn-label just-icon"><i class="icon-social-twitter"></i>
                                    </span>
                                </a>
                                <a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#">
                                    <span class="btn-label just-icon"><i class="icon-social-facebook"></i>
                                    </span>
                                </a>
                                <a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#">
                                    <span class="btn-label just-icon"><i class="icon-social-instagram"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="view-profile mb-2">
                                <a href="<?= site_url('member/dashboard/profile') ?>" class="btn btn-secondary w-100">View Full Profile</a>
                            </div>
                            <form id="profileImageForm" action="<?= site_url('member/dashboard/upload_profile_image') ?>" method="post" enctype="multipart/form-data">
                                <label for="profileImageInput" class="btn btn-sm btn-primary w-100 text-white mb-0" style="cursor:pointer;">
                                    <i class="fa fa-camera"></i> Change Photo
                                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" style="display:none;">
                                </label>
                                <div id="selectedFileName" class="mt-2 text-center text-secondary" style="display:none; background: #fff; border-radius: 4px; padding: 2px 8px; font-size: 13px;"></div>
                                <button type="submit" id="saveProfileImageBtn" class="btn btn-success btn-sm w-100 mt-2" style="display:none;">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</div>

<!-- Select2 -->
<script type="text/javascript">
var base_url = "<?= base_url() ?>";
$(document).ready(function() {

    loading_processing();
    openLoading = function() {
        loadingPannel.show();
    }

});
</script>