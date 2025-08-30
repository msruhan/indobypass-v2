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
        }
    }
</style>
<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">IMEI Service Details</h3>
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
                <a href="#">IMEI Services</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Detail</a>
            </li>
        </ul>
    </div>
</div>


<div class="row">
    <!-- Kiri: Description -->
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <div class="card-title">
                    <h6 style="font-size: 22;"><b><?= $data[0]['Title'] ?></b></h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id="autoExpandTextarea" style="border:none;resize:none;min-height:60px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kanan: Card Download, Video, Price, Information -->
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
        <div class="d-flex flex-column gap-3">
            <!-- Card Download -->
            <div class="card mb-2 shadow-sm border-success">
                <div class="card-header">
                    <div class="card-title"><i class="fa fa-download"></i> Download Tools</div>
                </div>
                    <div class="card-body pb-0 text-center card-download-body">
                    <?php if (!empty($download)): ?>
                        <a href="<?= htmlspecialchars($download) ?>" target="_blank" class="btn btn-success btn-lg mb-3">
                            <i class="fa fa-download"></i> Download Tools
                        </a>
                        <p class="text-muted">Klik tombol di atas untuk mengunduh tools yang dibutuhkan.</p>
                    <?php else: ?>
                        <span class="text-muted">No download available for this service.</span>
                    <?php endif; ?>
                    <div class="separator-dashed"></div>
                </div>
            </div>
            <!-- Card Video -->
            <div class="card mb-2 shadow-sm border-danger">
                <div class="card-header">
                    <div class="card-title">Tutorial Video</div>
                </div>
                    <div class="card-body pb-0 text-center card-video-body">
                    <?php if (!empty($video)): ?>
                        <?php
                        function getYoutubeId($url) {
                            $regExp = '/^.*(youtu.be\\/|v\\/|u\\/\\w\\/|embed\\/|watch\\?v=|\\&v=)([^#\\&\\?]*).*/';
                            if (preg_match($regExp, $url, $match)) {
                                return (strlen($match[2]) == 11) ? $match[2] : null;
                            }
                            return null;
                        }
                        $videoId = getYoutubeId($video);
                        ?>
                        <?php if ($videoId): ?>
                            <a href="<?= htmlspecialchars($video) ?>" target="_blank">
                                <div style="width:100%;aspect-ratio:16/9;overflow:hidden;">
                                    <img src="https://img.youtube.com/vi/<?= $videoId ?>/hqdefault.jpg" alt="Video Thumbnail" style="width:100%;height:100%;object-fit:cover;" class="rounded mb-2">
                                </div>
                            </a>
                        <?php endif; ?>
                        <a href="<?= htmlspecialchars($video) ?>" target="_blank" class="btn btn-danger btn-lg mb-3">
                            <i class="fa fa-play"></i> Watch Video
                        </a>
                        <p class="text-muted">Tonton video tutorial untuk panduan penggunaan layanan.</p>
                    <?php else: ?>
                        <span class="text-muted">No video available for this service.</span>
                    <?php endif; ?>
                    <div class="separator-dashed"></div>
                </div>
            </div>
            <!-- Card Price -->
            <div class="card mb-2">
                <div class="card-header">
                    <div class="card-title" style="color: green; font-size: 20px;">Price</div>
                </div>
                <div class="card-body pb-0">
                    <p style="color: green; font-size: 16px;"><?= format_currency($data[0]['Price']) ?></p>
                </div>
            </div>
            <!-- Card Information -->
            <div class="card mb-2">
                <div class="card-header">
                    <div class="card-title" style="color: darkgreen; font-size: 20px;">Information</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div id="payee_account" class="form-bg1 recevier-det">
                            <ul>
                                <li class="mt-2">Delivery Time : <b style="color: darkgreen;"><?= $data[0]['DeliveryTime'] ?></b></li>
                                <li class="mt-2">Order Type : 
                                    <b style="color: green;">
                                        <?php if ($data[0]['FieldType'] == '1') echo 'IMEI' ?>
                                        <?php if ($data[0]['FieldType'] == '2') echo 'Serial Number' ?>
                                        <?php if ($data[0]['FieldType'] == '3') echo 'Universal' ?>
                                    </b>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 & Dynamic Card Download/Video -->
<script type="text/javascript">
    var base_url = "<?= base_url() ?>";

    function setHtmlAndResize(html) {
        const div = document.getElementById('autoExpandTextarea');
        div.innerHTML = html;
        div.style.height = 'auto';
        div.style.height = div.scrollHeight + 'px';
        div.style.overflow = 'hidden';
        div.style.resize = 'none';
        div.style.border = 'none';
    }

    // Dynamic Download & Video Card
    function updateDownloadAndVideoCard(download, video) {
        // Download
        if (download) {
            $(".card-download-body").html(
                '<a href="' + download + '" target="_blank" class="btn btn-success mb-3">Download Tools <i class="fa fa-download"></i></a>' +
                '<div class="separator-dashed"></div>'
            );
        } else {
            $(".card-download-body").html(
                '<span class="text-muted">No download available for this service.</span><div class="separator-dashed"></div>'
            );
        }
        // Video
        if (video) {
            // Extract YouTube video ID
            function getYoutubeId(url) {
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                var match = url.match(regExp);
                return (match && match[2].length == 11) ? match[2] : null;
            }
            var videoId = getYoutubeId(video);
            if (videoId) {
                var thumb = 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg';
                var html = '<a href="' + video + '" target="_blank">' +
                    '<div style="width:100%;aspect-ratio:16/9;overflow:hidden;">' +
                        '<img src="' + thumb + '" alt="Video Thumbnail" style="width:100%;height:100%;object-fit:cover;" class="rounded mb-2">' +
                    '</div>' +
                    '</a>';
                html += '<div class="mt-3"></div>';
                html += '<div><a href="' + video + '" target="_blank" class="btn btn-danger btn-sm mb-3">Watch Video <i class="fa fa-play"></i></a></div>';
                html += '<div class="separator-dashed"></div>';
                $(".card-video-body").html(html);
            } else {
                $(".card-video-body").html('<span class="text-muted">Invalid YouTube link.</span><div class="separator-dashed"></div>');
            }
        } else {
            $(".card-video-body").html('<span class="text-muted">No video available for this service.</span><div class="separator-dashed"></div>');
        }
    }

    const longHtml = `<?php echo $data[0]['Description'] ?>`;
    window.addEventListener('load', () => {
        setHtmlAndResize(longHtml);
        // Update download & video card
        updateDownloadAndVideoCard(
            "<?= isset($download) ? addslashes($download) : '' ?>",
            "<?= isset($video) ? addslashes($video) : '' ?>"
        );
    });
</script>