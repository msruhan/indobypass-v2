<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<style>
    @media screen and (max-width: 767px) {
    .main-panel .page-header .breadcrumbs {
        margin-left: 0;
        padding-top: 5px;
        padding-left: 5px;
        padding-bottom: 0;
        border-left: 0;
    }
}
</style>
<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold">Payment History</h3>
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
                <a href="#">Payment History </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">History</div>
                <ul class="nav nav-tabs card-header-tabs" id="historyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="credits-tab" data-bs-toggle="tab" data-bs-target="#credits" type="button" role="tab" aria-controls="credits" aria-selected="true">Credits History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deposit-tab" data-bs-toggle="tab" data-bs-target="#deposit" type="button" role="tab" aria-controls="deposit" aria-selected="false">Deposit History</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="historyTabsContent">
                    <div class="tab-pane fade show active" id="credits" role="tabpanel" aria-labelledby="credits-tab">
                        <div class="table-responsive p-0">
                            <table id="table_data_credit" class="table table-sm table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Created at</th>
                                        <th>Code</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">
                        <div class="table-responsive p-0">
                            <table id="table_data_deposit" class="table table-sm table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>OrderID</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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

    const windowWidth = window.innerWidth;
    if(windowWidth > 500){
        $('.table-responsive').removeClass('p-0');
    }

    // Credits History DataTable
    new DataTable('#table_data_credit', {
        ajax: {
            url: base_url + "member/dashboard/credit_new",
            type: 'POST',
            data: {},
            dataSrc: function(json) {
                console.log('Credits AJAX Response:', json);
                return json.data;
            },
            error: function(xhr, status, error) {
                console.log('Credits AJAX Error:', error);
                console.log('Credits AJAX Status:', status);
                console.log('Credits AJAX XHR:', xhr);
            }
        },
        columns: [
            { data: "no" },
            { data: "created_at" },
            { data: "code" },
            { data: "amount" },
            { data: "description" },
            {
                data: "status",
                render: function(data, type, row) {
                    if (data === 'Paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    } else if (data === 'Refunded') {
                        return '<span class="badge bg-danger">Refunded</span>';
                    } else if (data) {
                        return '<span class="badge bg-secondary">' + data + '</span>';
                    } else {
                        return '';
                    }
                }
            },
        ],
        pagingType: "input",
        processing: true,
        serverSide: true,
        bInfo: false,
        ordering: false,
        deferRender: true,
        searching: true,
    });
    // Deposit History DataTable
    new DataTable('#table_data_deposit', {
        ajax: {
            url: base_url + "member/dashboard/deposit_history",
            type: 'POST',
            data: {},
            dataSrc: function(json) {
                console.log('Deposit AJAX Response:', json);
                return json.data;
            },
            error: function(xhr, status, error) {
                console.log('Deposit AJAX Error:', error);
                console.log('Deposit AJAX Status:', status);
                console.log('Deposit AJAX XHR:', xhr);
            }
        },
        columns: [
            { data: "ID" },
            { data: "OrderID" },
            { data: "Description" },
            { data: "Amount" },
            {
                data: "TransactionStatus",
                render: function(data, type, row) {
                    if (data === 'settlement') {
                        return '<span class="badge bg-success">Success</span>';
                    } else if (data) {
                        return '<span class="badge bg-secondary">' + data + '</span>';
                    } else {
                        return '';
                    }
                }
            },
            { data: "CreatedDateTime" },
        ],
        pagingType: "input",
        processing: true,
        serverSide: true,
        bInfo: false,
        ordering: false,
        deferRender: true,
        searching: true,
    });
});
</script>