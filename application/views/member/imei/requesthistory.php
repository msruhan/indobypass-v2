</style>

<!-- Debug script: Check if all DataTables Buttons JS files are loaded -->
<script>
// List of required DataTables Buttons JS files
const requiredDTFiles = [
  'dataTables.buttons.min.js',
  'buttons.html5.min.js',
  'buttons.print.min.js',
  'jszip.min.js',
  'pdfmake.min.js',
  'vfs_fonts.js'
];
window.addEventListener('DOMContentLoaded', function() {
  const loadedScripts = Array.from(document.getElementsByTagName('script')).map(s => s.src);
  let missing = [];
  requiredDTFiles.forEach(f => {
    if (!loadedScripts.some(src => src.includes(f))) {
      missing.push(f);
    }
  });
  // if (missing.length > 0) {
  //   console.error('Missing DataTables Buttons JS files:', missing);
  // } else {
  //   console.log('All DataTables Buttons JS files appear to be loaded.');
  // }
});
</script>
<style>
    /* Responsif Breadcrumbs */
    @media screen and (max-width: 767px) {
        .main-panel .page-header .breadcrumbs {
            margin-left: 0;
            padding-top: 5px;
            padding-left: 5px;
            padding-bottom: 0;
            border-left: 0;
        }
    }

    /* Hide "Status", "Detail", and "Service" columns on smartphone */
    @media screen and (max-width: 767px) {
        .column-status, .column-details, .column-service {
            display: none;
        }
    }

    /* Styling Modal */
    .modal-content {
        padding: 0.1rem;
    }
    .modal-header {
        background-color: #080017;
        color: white;
    }
    .details-container {
        text-align: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .details-row {
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .soft-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .soft-value {
        font-size: 1rem;
        font-weight: 500;
    }

    #statusFilter,
    #startDate,
    #endDate {
        border-radius: 0.375rem;
    }

    #applyFilter, #resetFilter {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        height: 32px;
    }

    @media (max-width: 576px) {
        #applyFilter, #resetFilter {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .row.mb-3.align-items-end.g-3 {
            flex-direction: column;
        }
    }

    .filter-row {
        margin-left: 5px; /* sesuaikan angka agar pas */
    }

.progress-bar {
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

#codeModal table td, #codeModal table th {
    font-size: 0.85rem;
    vertical-align: middle;
}


</style>

<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">IMEI Orders</h3>
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
                <a href="#">IMEI Orders</a>
            </li>
        </ul>
    </div>
</div>


<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header custom-card-header">
                <div class="card-title">IMEI Order History</div>
            </div>
 
            <div class="row mb-3 align-items-end g-3 filter-row">
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label fw-semibold">Status</label>
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="Issued">Success</option>
                        <option value="Pending">Pending</option>
                        <option value="In process">In process</option>
                        <option value="Canceled">Rejected</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="startDate" class="form-label fw-semibold">Start</label>
                    <input type="date" id="startDate" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label for="endDate" class="form-label fw-semibold">End</label>
                    <input type="date" id="endDate" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold d-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button id="applyFilter" class="btn btn-sm btn-secondary px-3">Filter</button>
                        <button id="resetFilter" class="btn btn-sm btn-outline-secondary px-3">Reset</button>
                        <button id="exportData" class="btn btn-sm btn-primary px-3">Export</button>
                    </div>
            </div>

            <div class="card-body custom-card-body">
                <div class="row">
                    <div class="table-responsive p-0">
                        <!-- Projects table -->
                        <table id="table_data_imei" class="table table-sm table-striped table-hover" style="width:98%;font-size:32px">
                            <thead>
                                <tr>
                                    <th class="column-actions" style="width: 1%;"></th>
                                    <th style="width: 1%;">ID</th>
                                    <th style="width: 10%;">Date</th>
                                    <th style="width: 10%;">Device</th>
                                    <th class="column-service" style="width: 30%;">Service</th>
                                    <th class="column-price" style="width: 5%;">Price</th>
                                    <th class="column-status" style="width: 5%;">Status</th>
                                    <th class="column-details" style="width: 5%;">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailImeiOrderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow rounded-3 border-0">
      <div class="modal-header bg-light border-bottom-0">
        <i class="fas fa-info-circle me-2 text-primary"></i>
        <h5 class="modal-title text-primary fw-bold">IMEI Order Detail</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-6"><div class="soft-label">Service</div><div class="soft-value" id="titleModal">N/A</div></div>
          <div class="col-6"><div class="soft-label">IMEI</div><div class="soft-value" id="imeiModal">N/A</div></div>
          <div class="col-6"><div class="soft-label">Price</div><div class="soft-value" id="priceModal">N/A</div></div>
          <div class="col-6"><div class="soft-label">Status</div><div class="soft-value" id="statusModal">N/A</div></div>
          <div class="col-12"><div class="soft-label">Note</div><div class="soft-value" id="noteModal">N/A</div></div>
          <div class="col-6"><div class="soft-label">Order Time</div><div class="soft-value" id="createdAtModal">N/A</div></div>
          <div class="col-6"><div class="soft-label">Reply Time</div><div class="soft-value" id="replyAtModal">N/A</div></div>
          <!-- <div class="col-12"><div class="soft-label">Reply</div><div class="soft-value" id="codeModal">N/A</div></div> -->
          <ul class="nav nav-tabs" id="codeTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="raw-tab" data-bs-toggle="tab" data-bs-target="#rawCode" type="button" role="tab">Raw</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#tableCode" type="button" role="tab">Table</button>
            </li>
            </ul>
            <div class="tab-content mt-2">
                <div class="tab-pane fade show active" id="rawCode" role="tabpanel">
                    <div id="codeRawContainer"></div>
                </div>
                <div class="tab-pane fade" id="tableCode" role="tabpanel">
                    <div id="codeTableContainer"></div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

</style>



<!-- RECOMMENDED CDN ORDER: (ALL 1.13.6) -->
<!-- 1. jQuery (only ONCE, before everything else) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script>console.log('[DEBUG] jQuery loaded:', typeof jQuery !== 'undefined' ? jQuery.fn.jquery : 'NOT LOADED'); window.jQuery = jQuery;</script> -->
<!-- 2. DataTables CSS & JS (core) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- <script>console.log('[DEBUG] DataTables core loaded:', typeof $.fn.dataTable !== 'undefined' ? $.fn.dataTable.version : 'NOT LOADED');</script> -->


<!-- 3. DataTables Buttons CSS & JS (use 2.4.1, compatible with 1.13.6) -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>// console.log('[DEBUG] JSZip loaded:', typeof JSZip !== 'undefined');</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script>// console.log('[DEBUG] pdfmake loaded:', typeof pdfMake !== 'undefined');</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>// console.log('[DEBUG] vfs_fonts loaded');</script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script>// console.log('[DEBUG] DataTables Buttons loaded (cdn.datatables.net 2.4.1):', typeof $.fn.dataTable !== 'undefined' && typeof $.fn.dataTable.Buttons !== 'undefined' ? 'LOADED' : 'NOT LOADED');</script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script>// console.log('[DEBUG] DataTables Buttons HTML5 loaded');</script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>// console.log('[DEBUG] DataTables Buttons Print loaded');</script>

<script type="text/javascript">
// DEBUG: Versi dan status jQuery/DataTables/Buttons
// console.log('jQuery version:', typeof jQuery !== 'undefined' ? jQuery.fn.jquery : 'NOT LOADED');
// console.log('DataTables core:', typeof $.fn.dataTable !== 'undefined' ? $.fn.dataTable.version : 'NOT LOADED');
// console.log('DataTables Buttons:', typeof $.fn.dataTable !== 'undefined' && typeof $.fn.dataTable.Buttons !== 'undefined' ? 'LOADED' : 'NOT LOADED');
var base_url = "<?= base_url() ?>";
$(document).ready(function () {
    var base_url = "<?= base_url() ?>";


    var table = $("#table_data_imei").DataTable({
        ajax: {
            url: base_url + "member/dashboard/listener_new",
            type: 'POST',
            data: function (d) {
                d.status = $('#statusFilter').val();
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
            },
            dataSrc: function(json) {
                return json.data;
            }
        },
        columns: [
            {
                data: null,
                className: 'details-control column-actions',
                defaultContent: '<button class="btn btn-secondary btn-round btn-xs toggle-detail"><i class="fas fa-chevron-down"></i></button>',
                orderable: false
            },
            { data: "no" },
            { data: "created_at" },
            { data: "imei" },
            { data: "service", className: 'column-service' },
            { data: "price", className: 'column-price' },
            { data: "status", className: 'column-status',
                render: function(data, type, row) {
                    return formatBadge(data);
                }
            },
            {
                data: null,
                className: 'column-details',
                defaultContent: '<button class="btn btn-info btn-xs view-detail">View</button>',
                orderable: false
            },
        ],
        pagingType: "input",
        processing: true,
        serverSide: true,
        bInfo: false,
        ordering: false,
        deferRender: true,
        searching: true,
        dom: 'Bfrtip',

        buttons: [
        {
            extend: 'csvHtml5',
            text: 'Export CSV',
            title: 'IMEI_Order_History',
            exportOptions: {
                columns: [1,2,3,4,5,6],
                format: {
                body: function (data, row, column, node) {
                    if (!data) return '';

                    // Hapus HTML
                    let text = data.replace(/<[^>]*>/g, '').trim();

                    // Bersihkan JSON-like array ["..."] -> ...
                    text = text.replace(/^\[\"|\"\]$/g, '').replace(/\"/g, '');
                    text = text.replace(/","/g, ' | '); // koma jadi separator aman

                    // Gabungkan field harga/status biar tidak pecah kolom
                    text = text.replace(/,\s*Rp/g, ' | Rp');
                    text = text.replace(/,\s*Issued/g, ' | Issued');
                    text = text.replace(/,\s*Canceled/g, ' | Canceled');

                    // Rapikan format harga (Rp 1.233.700,00 → Rp 1233700.00)
                    text = text.replace(/Rp\s([\d\.]+),(\d{2})/g, function(_, num, dec) {
                        return 'Rp ' + num.replace(/\./g, '') + '.' + dec;
                    });

                    return text;
                }
            }

            },
            filename: 'IMEI_Order_History',
            className: 'd-none'
        },
        {
            extend: 'excelHtml5',
            text: 'Export Excel',
            title: 'IMEI_Order_History',
            exportOptions: {
            columns: [1,2,3,4,5,6],
            format: {
                    body: function (data, row, column, node) {
                        if (!data) return '';

                        // Hapus HTML
                        let text = data.replace(/<[^>]*>/g, '').trim();

                        // Bersihkan JSON-like array ["..."] -> ...
                        text = text.replace(/^\[\"|\"\]$/g, '').replace(/\"/g, '');
                        text = text.replace(/","/g, ' | '); // koma jadi separator aman

                        // Gabungkan field harga/status biar tidak pecah kolom
                        text = text.replace(/,\s*Rp/g, ' | Rp');
                        text = text.replace(/,\s*Issued/g, ' | Issued');
                        text = text.replace(/,\s*Canceled/g, ' | Canceled');

                        // Rapikan format harga (Rp 1.233.700,00 → Rp 1233700.00)
                        text = text.replace(/Rp\s([\d\.]+),(\d{2})/g, function(_, num, dec) {
                            return 'Rp ' + num.replace(/\./g, '') + '.' + dec;
                        });

                        return text;
                    }
                }

            },
            filename: 'IMEI_Order_History',
            className: 'd-none'
        }
    ]
    });



    // Tombol START FILTER
    $('#applyFilter').on('click', function () {
        table.ajax.reload();
    });

    // Tombol RESET FILTER
    $('#resetFilter').on('click', function () {
        $('#statusFilter').val('');
        $('#startDate').val('');
        $('#endDate').val('');
        table.ajax.reload();
    });

    // Tombol EXPORT (langsung download dari backend, semua data sesuai filter)
    $('#exportData').on('click', function () {
        var status = $('#statusFilter').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        if (!startDate && !endDate) {
            if (!confirm('Anda belum memilih tanggal. Export semua data?')) return;
        }
        // Build URL
        var url = base_url + 'member/dashboard/export_imei_orders?status=' + encodeURIComponent(status) + '&startDate=' + encodeURIComponent(startDate) + '&endDate=' + encodeURIComponent(endDate);
        window.open(url, '_blank');
    });

    // Detail toggle & modal (tetap seperti sebelumnya)
    $('#table_data_imei tbody').on('click', 'button.toggle-detail', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
            $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');

            tr.next('tr').find('button.view-detail-inline').off('click').on('click', function () {
                var data = row.data();
                if (data) {
                    $('#titleModal').text(data.service || 'N/A');
                    $('#imeiModal').text(data.imei || 'N/A');
                    $('#priceModal').text(data.price || 'N/A');
                    $('#statusModal').html(formatBadge(data.status));
                    var replyCode = row.data().code || '';
                    $('#codeRawContainer').html(`<em>${replyCode}</em>`);

                    if (/roamer/i.test(replyCode)) {
                        $('#codeTableContainer').html(formatReplyAsTable(replyCode));
                        $('#table-tab').parent().show();
                        $('#codeTab').show();
                    } else {
                        $('#codeTableContainer').html('');
                        $('#table-tab').parent().hide();
                        $('#raw-tab').tab('show');
                    }
                    $('#noteModal').text(data.note || 'N/A');
                    $('#createdAtModal').text(data.created_at || 'N/A');
                    $('#replyAtModal').text(data.updated_date_time || 'N/A');

                    $('#detailImeiOrderModal').modal('show');
                }
            });
        }
    });


    $('#table_data_imei tbody').on('click', 'button.view-detail, button.view-detail-inline', function () {
        var tr = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev() : $(this).closest('tr');
        var row = table.row(tr);

        if (row.data()) {
            $('#titleModal').text(row.data().service || 'N/A');
            $('#imeiModal').text(row.data().imei || 'N/A');
            $('#priceModal').text(row.data().price || 'N/A');
            $('#statusModal').html(formatBadge(row.data().status));
            var replyCode = row.data().code || '';
            $('#codeRawContainer').html(`<em>${replyCode}</em>`);

            if (/roamer/i.test(replyCode)) {
                $('#codeTableContainer').html(formatReplyAsTable(replyCode));
                $('#table-tab').parent().show();
                $('#codeTab').show();
            } else if (/check/i.test(replyCode)) {
                $('#codeTableContainer').html(formatReplyAsTable(replyCode));
                $('#table-tab').parent().show();
                $('#codeTab').show();
            } else {
                $('#codeTableContainer').html('');
                $('#table-tab').parent().hide();
                $('#raw-tab').tab('show');
            }
            // Debug note value
            // console.log('[DEBUG] note value:', row.data().note);
            $('#noteModal').text(row.data().note || 'N/A');
            $('#createdAtModal').text(row.data().created_at || 'N/A');
            $('#replyAtModal').text(row.data().updated_date_time || 'N/A');

            $('#detailImeiOrderModal').modal('show');
        }
    });

    function format(d) {
        return `
            <div class="details-container">
                <div class="details-row"><strong>Service:</strong> ${d.service || 'N/A'}</div>
                <div class="details-row"><strong>Status:</strong> ${formatBadgeSmall(d.status)}</div>
                <div class="details-row"><strong>Detail:</strong> <button class="btn btn-info btn-xs view-detail-inline">View</button></div>
            </div>
        `;
    }

    function extractTextFromHTML(htmlString) {
        var tempDiv = document.createElement("div");
        tempDiv.innerHTML = htmlString;
        return tempDiv.textContent || tempDiv.innerText || "";
    }

    function formatBadge(status) {
    var normalizedStatus = extractTextFromHTML(status).trim();
    switch (normalizedStatus) {
        case 'Success':
            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                        Success
                    </div>
                </div>`;
        case 'Pending':
            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 70%">
                        Pending...
                    </div>
                </div>`;
        case 'In process':
            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" role="progressbar" style="width: 90%">
                        In process...
                    </div>
                </div>`;
        case 'Rejected':
            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%">
                        Rejected
                    </div>
                </div>`;
        default:
            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%">
                        N/A
                    </div>
                </div>`;
    }
}

function formatBadgeSmall(status) {
    var normalizedStatus = extractTextFromHTML(status).trim();
    let className = '';
    let label = '';

    switch (normalizedStatus) {
        case 'Success':
            className = 'bg-success';
            label = 'Success';
            break;
        case 'Pending':
            className = 'bg-warning';
            label = 'Pending';
            break;
        case 'In process':
            className = 'bg-secondary';
            label = 'In process';
            break;
        case 'Rejected':
            className = 'bg-danger';
            label = 'Rejected';
            break;
        default:
            className = 'bg-secondary';
            label = 'N/A';
    }

    return `<span class="badge ${className}" style="font-size: 0.75rem; padding: 0.4em 0.6em;">${label}</span>`;
}

function formatReplyAsTable(replyText) {
    if (!replyText || typeof replyText !== 'string') return 'N/A';

    // Ambil bagian utama (buang header jika ada)
    const parts = replyText.split(/\[\d+\]/).filter(p => p.trim() !== '');
    const headerMatch = replyText.match(/History Check for IMEI:\s*([^\[]+)/i);
    const imeiHeader = headerMatch ? `<strong>History Check for IMEI:</strong> ${headerMatch[1].trim()}` : '';

    let tableHTML = `<div>${imeiHeader}</div><div class="table-responsive"><table class="table table-sm table-bordered mt-2">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>Action</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>`;

    parts.forEach((part, index) => {
        const dateMatch = part.match(/Date:\s*([^,]+)/i);
        const imeiMatch = part.match(/IMEI:\s*([^,]+)/i);
        const imsiMatch = part.match(/IMSI:\s*([^,]+)/i);
        const actionMatch = part.match(/Action:\s*([^,]+)/i);
        const noteMatch = part.match(/Note:\s*(.+)$/i);
        const statusMatch = part.match(/Status:\s*([^,]+)/i); // Extract "Status"

        tableHTML += `<tr>
            <td>${index + 1}</td>
            <td>${dateMatch ? dateMatch[1].trim() : '-'}</td>
            <td>${imeiMatch ? imeiMatch[1].trim() : '-'}</td>
            <td>${imsiMatch ? imsiMatch[1].trim() : '-'}</td>
            <td>${actionMatch ? actionMatch[1].trim() : '-'}</td>
            <td>${noteMatch ? noteMatch[1].trim() : (statusMatch ? statusMatch[1].trim() : '-')}</td> <!-- Include Status in Note -->
        </tr>`;
    });

    tableHTML += `</tbody></table></div>`;
    return tableHTML;
}
});

</script>

