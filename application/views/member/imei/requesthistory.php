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
            <div class="card-body custom-card-body">
                <div class="row">
                    <div class="table-responsive p-0">
                        <!-- Projects table -->
                        <table id="table_data_imei" class="table table-sm table-striped table-hover" style="width:100%;font-size:32px">
                            <thead>
                                <tr>
                                    <th class="column-actions" style="width: 1%;"></th>
                                    <th style="width: 1%;">ID</th>
                                    <th style="width: 10%;">Date</th>
                                    <th style="width: 10%;">Device</th>
                                    <th class="column-service" style="width: 40%;">Service</th>
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
          <div class="col-12"><div class="soft-label">Reply</div><div class="soft-value" id="codeModal">N/A</div></div>
        </div>
      </div>
    </div>
  </div>
</div>

</style>
<!-- Select2 -->
<script type="text/javascript">
var base_url = "<?= base_url() ?>";
$(document).ready(function() {
    var base_url = "<?= base_url() ?>";

    // Initialize DataTable
    var table = $("#table_data_imei").DataTable({
        ajax: {
            url: base_url + "member/dashboard/listener_new",
            type: 'POST',
            data: {}
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
            { data: "status", className: 'column-status' },
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
        searching: true
    });

    // Handle click event for the 'Toggle' button
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
        }
    });

    // Handle click event for 'View Details' button in the main table column
    $('#table_data_imei tbody').on('click', 'button.view-detail', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.data()) {
            console.log("Row data:", row.data());

            // Populate modal with row data
            $('#titleModal').text(row.data().service || 'N/A');
            $('#imeiModal').text(row.data().imei || 'N/A');
            $('#priceModal').text(row.data().price || 'N/A');
            $('#statusModal').html(formatBadge(row.data().status));
            $('#codeModal').html(row.data().code || 'N/A');  // Use .html() here
            $('#noteModal').text(row.data().note || 'N/A');
            $('#createdAtModal').text(row.data().created_at || 'N/A');
            $('#replyAtModal').text(row.data().updated_date_time || 'N/A');

            // Show the modal
            $('#detailImeiOrderModal').modal('show');
        } else {
            console.error('No data available for this row.');
        }
    });

    // Handle click event for 'View Details' button in expandable row
    $('#table_data_imei tbody').on('click', 'button.view-detail-inline', function () {
        var tr = $(this).closest('tr').prev(); // The row is the previous sibling (because it was expanded)
        var row = table.row(tr);

        if (row.data()) {
            console.log("Row data inline:", row.data());

            // Populate modal with row data
            $('#titleModal').text(row.data().service || 'N/A');
            $('#imeiModal').text(row.data().imei || 'N/A');
            $('#priceModal').text(row.data().price || 'N/A');
            $('#statusModal').html(formatBadge(row.data().status));
            $('#codeModal').html(row.data().code || 'N/A');  // Use .html() here
            $('#noteModal').text(row.data().note || 'N/A');
            $('#createdAtModal').text(row.data().created_at || 'N/A');
            $('#replyAtModal').text(row.data().updated_date_time || 'N/A');

            // Show the modal
            $('#detailImeiOrderModal').modal('show');
        } else {
            console.error('No data available for this row.');
        }
    });

    function format(d) {
        return `
            <div class="details-container">
                <div class="details-row">
                    <strong>Service:</strong> ${d.service || 'N/A'}
                </div>
                <div class="details-row">
                    <strong>Status:</strong> ${formatBadge(d.status)}
                </div>
                <div class="details-row">
                    <strong>Detail:</strong> <button class="btn btn-info btn-xs view-detail-inline">View</button>
                </div>
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
        console.log("Normalized status:", normalizedStatus);

        switch (normalizedStatus) {
            case 'Success':
                return "<span class='badge bg-success'>Success</span>";
            case 'Pending':
                return "<span class='badge bg-warning'>Pending</span>";
            case 'In Process':
                return "<span class='badge bg-warning'>In Process</span>";
            case 'Rejected':
                return "<span class='badge bg-danger'>Rejected</span>";
            default:
                return "<span class='badge bg-secondary'>In</span>";
        }
    }
});
</script>