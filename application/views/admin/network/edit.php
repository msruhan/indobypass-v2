<div class="portlet">
    <div class="portlet-body form">
        
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h3 style="padding:10px;">Group Edit</h3>
        </div>

        <!-- FORM EDIT GROUP -->
        <?php echo form_open_multipart("admin/network/update",array('id'=>"network-validate")); ?> 
        <input type="hidden" name="ID" value="<?php echo $data[0]['ID'] ?>" />
        <div class="form-body">
            <div class="form-group">
                <label>Group Title:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-font"></i>
                    </span>
                    <?php echo form_input(array('name'=>"Title",'id'=>"Title", 'class'=>"form-control", 'value'=>set_value('Title', $data[0]['Title']))); ?>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-info">Submit</button>
        </div>
        <?php echo form_close(); ?>

        <?php
        // Ambil daftar service yang termasuk ke dalam group ini
        // Diasumsikan $data[0]['ID'] adalah NetworkID
        // Dan model method_model sudah tersedia di controller
        if (!isset($this->method_model)) {
            $CI =& get_instance();
            $CI->load->model('method_model');
            $method_model = $CI->method_model;
        } else {
            $method_model = $this->method_model;
        }
        $services_in_group = $method_model->get_methods_by_network($data[0]['ID']);
        ?>
        <!-- TABEL SERVICES DALAM GROUP -->
        <div class="form-group">
            <label class="control-label" style="padding-bottom:6px;font-weight:600;">Services in this Group:</label>
            <div>
                <div style="background:#f8f9fa;border:1px solid #e3e6ea;border-radius:8px;padding:12px 18px 8px 18px;min-height:48px;">
                <?php if (!empty($services_in_group)) { ?>
                    <table class="table table-sm table-bordered mb-0" style="background:#fff;">
                        <thead>
                            <tr style="background:#f1f3f6;">
                                <th style="width:50px;"><input type="checkbox" id="check-all-services" title="Check All" /></th>
                                <th style="width:50px;">ID</th>
                                <th>Service Title</th>
                                <th style="width:110px;">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($services_in_group as $svc) { ?>
                            <tr>
                                <td><input type="checkbox" class="service-checkbox" name="service_ids[]" value="<?php echo $svc['ID']; ?>" /></td>
                                <td><?php echo $svc['ID']; ?></td>
                                <td><?php echo htmlspecialchars($svc['Title']); ?></td>
                                <td>
                                    <a href="<?php echo site_url('admin/method/edit/'.$svc['ID']); ?>" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo site_url('admin/method/delete/'.$svc['ID']); ?>" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-warning btn-sm mt-2" id="copy-desc-btn" style="margin-top:12px;">
                        <i class="fa fa-copy"></i> Copy Description
                    </button>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var btn = document.getElementById('copy-desc-btn');
                        if(btn) {
                            btn.onclick = function() {
                                // Data dari PHP
                                var services = <?php echo json_encode($services_in_group); ?>;
                                // Cari ID yang dicentang
                                var checked = document.querySelectorAll('.service-checkbox:checked');
                                if(checked.length === 0) return;
                                var selectedId = checked[0].value;
                                // Temukan data service yang dicentang
                                var selectedSvc = null;
                                for(var i=0; i<services.length; i++) {
                                    if(services[i].ID == selectedId) {
                                        selectedSvc = services[i];
                                        break;
                                    }
                                }
                                if(!selectedSvc) return;
                                // Siapkan nilai yang akan dicopy
                                var text = '';
                                text += 'Description: ' + (selectedSvc.Description || '') + '\n';
                                text += 'Download: ' + (selectedSvc.Download || '') + '\n';
                                text += 'Video: ' + (selectedSvc.Video || '') + '\n';
                                // Copy ke clipboard
                                navigator.clipboard.writeText(text).then(function() {
                                    btn.innerHTML = '<i class="fa fa-check"></i> Copied!';
                                    setTimeout(function(){ btn.innerHTML = '<i class="fa fa-copy"></i> Copy Description'; }, 1500);
                                });
                                // Paste ke semua service dalam group (AJAX call)
                                var idsToUpdate = [];
                                for(var i=0; i<services.length; i++) {
                                    idsToUpdate.push(services[i].ID);
                                }
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', '<?php echo site_url('admin/network/bulk_update_services'); ?>', true);
                                xhr.setRequestHeader('Content-Type', 'application/json');
                                xhr.onreadystatechange = function() {
                                    if(xhr.readyState === 4 && xhr.status === 200) {
                                        // Optionally, reload or show success
                                    }
                                };
                                xhr.send(JSON.stringify({
                                    ids: idsToUpdate,
                                    Description: selectedSvc.Description || '',
                                    Download: selectedSvc.Download || '',
                                    Video: selectedSvc.Video || ''
                                }));
                            }
                        }
                        // Checkbox: Check all services
                        var checkAll = document.getElementById('check-all-services');
                        if(checkAll) {
                            checkAll.addEventListener('change', function() {
                                var checkboxes = document.querySelectorAll('.service-checkbox');
                                for(var i=0; i<checkboxes.length; i++) {
                                    checkboxes[i].checked = checkAll.checked;
                                }
                            });
                        }
                    });
                    </script>
                <?php } else { ?>
                    <span class="text-muted">No services in this group.</span>
                <?php } ?>
                </div>
            </div>
        </div>

      
    </div>
</div>
