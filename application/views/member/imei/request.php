<style>
   @media screen and (max-width: 767px) {
    .main-panel .page-header .breadcrumbs {
        margin-left: 0;
        padding-top: 5px;
        padding-left: 5px;
        padding-bottom: 0;
        border-left: 0;
    }

    #autoExpandTextarea {
        border: none;
        resize: none;
        width: 100%; /* Lebar textarea 100% dari container */
        padding: 10px; /* Tambahkan padding agar teks tidak terlalu menempel */
        box-sizing: border-box; /* Agar padding diperhitungkan dalam lebar total */
    }
}

</style>
<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">Place Order</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= site_url() ?>member/dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Place IMEI Order</a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-primary card-round">
        <div class="card-body">
            <div class="row">
            <div class="col-3">
                <div class="icon-big text-center">
                <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="col-9 col-stats">
                <div class="numbers">
                <p class="card-category">Balance</p>
                <h4 class="card-title"><?= format_currency($credit) ?></h4>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?= $this->session->flashdata('message') ?>
        <?= form_error('IMEI', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <?= form_error('MethodID', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <?= form_error('Note', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Place IMEI Order</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php echo form_open('member/imeirequest/insert', array('role' => 'form', 'method' => 'post','id' => 'imeireq' ,'name' => 'form2', 'class' => 'form-horizontal', 'onsubmit' => 'openLoading()')); ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('imei_fields_method') ?></label>
                        <div class="col-12">
                            <select name="MethodID" id="MethodID" class="form-control" width="100%" required>
                                <option value=""><?php echo $this->lang->line('imei_fields_select') ?></option>
                                <?php foreach($imeimethods as $network): ?>
                                <?php if(!empty($network['methods'])): ?>
                                <optgroup label="<?php echo $network['Title'] ?>">
                                    <?php foreach($network['methods'] as $method): ?>
                                    <option value="<?php echo $method['ID'] ?>"
                                        <?php echo set_select('MethodID', $method['ID']); ?>>
                                        <?php echo $method['Title'].'- Only '.format_currency($method['Price']).' credit required'; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="load-field"></div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('imei_fields_imei_sr') ?></label>
                        <div class="col-12">
                            <textarea name="IMEI" id="IMEI"
                                placeholder="<?php echo $this->lang->line('imei_fields_imei_sr') ?>"
                                class="form-control" minlength="10" required><?php echo set_value('IMEI'); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang->line('imei_request_note') ?></label>
                        <div class="col-12">
                            <textarea name="Note" placeholder="<?php echo $this->lang->line('imei_request_note') ?>"
                                class="form-control"><?php echo set_value('Note'); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 text-center">
                            <button type="submit"
                                class="btn btn-primary btn-sm"><?php echo $this->lang->line('imei_fields_submit') ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Description</div>
            </div>
            <div class="card-body pb-0">
                <div id="load-field-text"><i class="text-muted" id="desc_service">Description for service selected!</i></div>
                <div class="separator-dashed"></div>
            </div>
        </div>
    </div>

    <!-- KANAN: Card Download & Video dalam satu kolom -->
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
        <!-- Card Download -->
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-title">Download</div>
            </div>
            <div class="card-body pb-0 text-center card-download-body">
                <span class="text-muted">No download available for this service.</span>
                <div class="separator-dashed"></div>
            </div>
        </div>
        <!-- Card Video -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tutorial Video</div>
            </div>
            <div class="card-body pb-0 text-center card-video-body">
                <span class="text-muted">No video available for this service.</span>
                <div class="separator-dashed"></div>
            </div>
        </div>
    </div>
    <!-- <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body pb-0">
                <div class="mb-5">
                    <p class="card-title mb-2">IMEI History</p>
                    <div class="d-flex">
                        <button class="btn btn-label-success btn-round active w-100"
                            onclick="historyOrderTable('Issued')">
                            Success <i class="fas fa-check-circle"></i> </button>
                    </div>
                    <div class="separator-dashed"></div>
                    <div class="d-flex">
                        <button class="btn btn-label-warning btn-round active w-100"
                            onclick="historyOrderTable('Pending')">
                            Pending <i class="fas fas fa-clock"></i> </button>
                    </div>
                    <div class="separator-dashed"></div>
                    <div class="d-flex">
                        <button class="btn btn-label-danger btn-round active w-100"
                            onclick="historyOrderTable('Canceled')">
                            Canceled <i class="fas fa-times-circle"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
 
</div>

<!-- modal for datatable imei history -->
<!-- Modal -->
<!-- <div class="modal fade" id="historyOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">IMEI History Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive p-3">
                    <table id="table_data_imei" class="table table-sm table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>IMEI</th>
                                <th>Service</th>
                                <th>Code</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal Popup -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-secondary text-white border-0 rounded-top-4">
        <h5 class="modal-title fw-semibold" id="orderSuccessLabel">Order Success</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-5">
        <h5 class="mb-3"><?= $this->session->flashdata('success_order'); ?></h5>
        <p class="mb-4 text-muted">Continue to place order?</p>

        <div class="d-flex justify-content-center gap-3">
          <a href="<?= site_url('member/imeirequest'); ?>" class="btn btn-secondary px-4">Click Here</a>
          <a href="<?= site_url('member/imeirequest/history'); ?>" class="btn btn-outline-secondary px-4">Order History</a>
        </div>
      </div>
    </div>
  </div>
</div>


<style>
.select2-container .select2-selection--single .select2-selection__rendered {
    /* line-height: 60px !important; */
    /* /* padding-left: 20px !important; */
    padding-top: 5px !important;
    */
}

.select2-container .select2-selection--single {
    height: 40px !important;
}
</style>
<!-- jQuery WAJIB PALING ATAS sebelum plugin lain -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables, Select2, dan plugin lain yang butuh jQuery -->
<script src="<?= site_url() ?>assets/assets_members/libs/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= site_url() ?>assets/assets_members/js/select2.min.js"></script>
<script src="<?= site_url() ?>assets/assets_members/js/kaiadmin.min.js"></script>
<script src="<?= site_url() ?>assets/assets_members/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= site_url() ?>assets/assets_members/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?= site_url() ?>assets/assets_members/libs/dataTables/dataTables-input.js"></script>

<script type="text/javascript">
var base_url = "<?= base_url() ?>";
$(document).ready(function() {

    loading_processing();
    openLoading = function() {
        loadingPannel.show();
    }

    var id = $("#MethodID").val();
    if (id != "") {
        var data = $("#imeireq").serialize();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('member/imeirequest/formfields'); ?>",
            data: data,
            cache: false,
            success: function(data) {
                $("#load-field").html('');
                $("#load-field").html(data);
            }
        });
    }
    $("#MethodID").change(function() {
        var id = $("#MethodID").val();
        if (id != "") {
            var data = $("#imeireq").serialize();
            $.ajax({
                type: "post",
                url: "<?php echo site_url('member/imeirequest/formfields'); ?>",
                data: data,
                cache: false,
                success: function(data) {
                    $("#desc_service").html('');
                    $("#load-field").html('');
                    $("#load-field").html(data);
                }
            });

            $.ajax({
                type: "post",
                url: "<?php echo site_url('member/imeirequest/formfieldstext'); ?>",
                dataType: "json",
                data: data,
                cache: false,
                success: function(data) {
                    $("#load-field-text").html('');
                    var html = '';

                    if (data.price_only || data.delivery_time) {
                        html +=
                            '<div class="form-group">' +
                            '<div class="d-flex flex-wrap align-items-center gap-3">';
                        if (data.price_only) {
                            html +=
                                '<div class="d-flex align-items-center">' +
                                    '<span class="badge bg-secondary fs-6" style="font-size:1rem;padding:8px 16px;">' + data.price_only + '</span>' +
                                '</div>';
                        }
                        if (data.delivery_time) {
                            html +=
                                '<div class="d-flex align-items-center">' +
                                    '<span class="badge bg-warning fs-6" style="font-size:1rem;padding:8px 16px;">' + data.delivery_time + '</span>' +
                                '</div>';
                        }
                        html += '</div></div>';
                    }

                    if (data.description) {
                        html +=
                            '<div class="form-group">' +
                            '<div class="col-sm-9 text"><div id="desc_service_html"></div></div>' +
                            '</div>';
                    }
                    $("#load-field-text").html(html);

                    if (data.description) {
                        // Use setTimeout to ensure the DOM has been updated
                        setTimeout(() => {
                            const descDiv = document.getElementById('desc_service_html');
                            if (descDiv) {
                                descDiv.innerHTML = data.description;
                            } else {
                                console.error('desc_service_html not found');
                            }
                        }, 0);
                    }
                    // Update Download card
                    if (typeof data.download !== 'undefined' && data.download) {
                        $(".card-download-body").html(
                            '<a href="' + data.download + '" target="_blank" class="btn btn-success mb-3">Download Tools <i class="fa fa-download"></i></a>' +
                            '<div class="separator-dashed"></div>'
                        );
                    } else {
                        $(".card-download-body").html(
                            '<span class="text-muted">No download available for this service.</span><div class="separator-dashed"></div>'
                        );
                    }

                    // Update Video card
                    if (typeof data.video !== 'undefined' && data.video) {
                        // Extract YouTube video ID
                        function getYoutubeId(url) {
                            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                            var match = url.match(regExp);
                            return (match && match[2].length == 11) ? match[2] : null;
                        }
                        var videoId = getYoutubeId(data.video);
                        if (videoId) {
                            var thumb = 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg';
                            var html = '<a href="' + data.video + '" target="_blank">' +
                                '<div style="width:100%;aspect-ratio:16/9;overflow:hidden;">' +
                                    '<img src="' + thumb + '" alt="Video Thumbnail" style="width:100%;height:100%;object-fit:cover;" class="rounded mb-2">' +
                                '</div>' +
                                '</a>';
                            html += '<div class="mt-3"></div>';
                            html += '<div><a href="' + data.video + '" target="_blank" class="btn btn-danger btn-sm mb-3">Watch Video <i class="fa fa-play"></i></a></div>';
                            html += '<div class="separator-dashed"></div>';
                            $(".card-video-body").html(html);
                        } else {
                            $(".card-video-body").html('<span class="text-muted">Invalid YouTube link.</span><div class="separator-dashed"></div>');
                        }
                    } else {
                        $(".card-video-body").html('<span class="text-muted">No video available for this service.</span><div class="separator-dashed"></div>');
                    }
                }
            });
        } else {
            $("#load-field").html('');
        }
    });
    $('#MethodID').select2();

});

  document.addEventListener("DOMContentLoaded", function () {
    const modalText = document.querySelector("#orderSuccessModal .modal-body h5");
    if (modalText && modalText.textContent.trim() !== "") {
      var orderModal = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
      orderModal.show();
    }
  });
</script>