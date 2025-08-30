<div class="row">
	<div class="col-md-12">
		<!-- BEGIN SAMPLE TABLE PORTLET-->
		<div class="portlet">
			
				<?php echo form_open('admin/method/add',array('method' => 'post','id'=>'form'));?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>IMEI Services Manager
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
						<table class="table table-hover" cellpadding="0" cellspacing="0" width="100%" id="TableDeferLoading">
						<thead>
							<tr>                                        
								<th width="3%"><input type="checkbox" id="selectAll"></th>
								<th width="5%">ID</th>
								<th>Title</th>
								<th width="10%">Status</th>
								<th width="10%">Price</th>
								<th width="15%">Created Date Time</th>
								<th width="10%">Options</th>
							</tr>
						</thead>
						<tfoot>
							<tr>                                        
								<th width="3%"></th>
								<th width="5%">ID</th>
								<th>Title</th>
								<th width="10%">Status</th>
								<th width="10%">Price</th>
								<th width="15%">Created Date Time</th>
								<th width="10%">Options</th>
							</tr>
						</tfoot>
					</table>
				</div>
<div style="display: flex; gap: 10px; align-items: center; margin-bottom:10px;">
  <!-- Modal Konfirmasi -->
  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body" id="confirmModalBody">
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
		  <button type="button" class="btn btn-primary" id="confirmSendBtn">Kirim</button>
		</div>
	  </div>
	</div>
  </div>
					<button id="sample_editable_1_new" class="btn btn-success" type="button" onclick="window.location.href='<?php echo site_url('admin/method/add') ?>'">Add Service <i class="fa fa-plus"></i></button>
					<button id="bulkSendBtn" class="btn btn-info" type="button">Send to Telegram</button>

					<!-- Modal Notifikasi -->
					<div class="modal fade" id="notifModal" tabindex="-1" role="dialog" aria-labelledby="notifModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="notifModalLabel">Notifikasi</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" id="notifModalBody">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						</div>
						</div>
					</div>
					</div>
				</div>
			</div>
			<?php
				echo form_close();
			?>
		</div>
		<!-- END SAMPLE TABLE PORTLET-->
	</div>
</div>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('assets_url');?>plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('assets_url');?>plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('assets_url');?>plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('assets_url');?>plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
<?php if($this->config->item('csrf_protection') === TRUE){	?>	
	$.ajaxSetup({
		headers: {
			'<?php echo $this->config->item('csrf_token_name'); ?>': '<?php echo $this->config->item('csrf_cookie_name'); ?>'
		}
	});
<?php }	?>	
	$('#TableDeferLoading').DataTable( {
		"processing": true,
		"serverSide": true,
		"deferRender": true,
		"ajax": "<?php echo site_url('admin/method/listener'); ?>",
		"order": [[ 0, 'asc' ]],
		"columnDefs": [
			{ "orderable": false, "targets": [-1] },
			{ "searchable": false, "targets": [-1] }
		],
		"columns": [
			{ 
				"data": null,
				"orderable": false,
				"searchable": false,
				"render": function (data, type, row) {
					return '<input type="checkbox" class="row-select" value="'+row.ID+'">';
				}
			},
			{ "data": "ID" },
			{ "data": "Title" },
			{ "data": "Status" },
			{ "data": "Price" },
			{ "data": "CreatedDateTime" },
			{ "data": "delete" }
		],
		"order": [[ 1, 'asc' ]], // order by ID (bukan kolom checkbox)
		"columnDefs": [
			{ "orderable": false, "targets": [0, -1] },
			{ "searchable": false, "targets": [0, -1] }
		]
	} );

	// Select all functionality
	$('#selectAll').on('click', function() {
		var checked = this.checked;
		$('.row-select').prop('checked', checked);
	});

	// Deselect 'select all' if any row is unchecked
	$('#TableDeferLoading').on('change', '.row-select', function() {
		if (!this.checked) {
			$('#selectAll').prop('checked', false);
		}
	});

	// Bulk send button
	var bulkSendIds = [];
	$('#bulkSendBtn').on('click', function(e) {
		e.preventDefault(); // cegah submit form
		var table = $('#TableDeferLoading').DataTable();
		var ids = [];
		table.rows().every(function() {
			var d = this.data();
			var node = this.node();
			if ($(node).find('.row-select').prop('checked')) {
				ids.push(d.ID);
			}
		});
		if (ids.length === 0) {
			showNotif('Pilih minimal satu service.');
			return;
		}
		bulkSendIds = ids;
		$('#confirmModalBody').html('Kirim <b>' + ids.length + '</b> service ke Telegram?');
		$('#confirmModal').modal('show');
	});

	$('#confirmSendBtn').on('click', function() {
		$('#confirmModal').modal('hide');
		if (bulkSendIds.length === 0) return;
		$.ajax({
			url: "<?php echo site_url('admin/method/send_telegram_bulk'); ?>",
			type: 'POST',
			data: { ids: bulkSendIds },
			dataType: 'json',
			success: function(data) {
				showNotif(data.message);
			},
			error: function() {
				showNotif('Gagal mengirim ke Telegram.');
			}
		});
		bulkSendIds = [];
	});

	function showNotif(msg) {
		$('#notifModalBody').html(msg);
		$('#notifModal').modal('show');
	}
} );

function editStatus(id){
	console.log(id);

	$.ajax({
		type: "post",
		url: "<?php echo site_url('admin/method/editStatus'); ?>",
		data: {
			"ID": id
		},
		cache: false,
		success: function(data) {
			window.location.reload();
		}
	});
}
</script>      
<script type="text/javascript">
function sendService(id) {
	// Ambil Title dari row DataTable
	var table = $('#TableDeferLoading').DataTable();
	var rowData = table.row(function(idx, data, node) {
		return data.ID == id;
	}).data();
	var title = rowData ? rowData.Title : '';
	if (!title) {
		alert('Title not found');
		return;
	}
	$.ajax({
		url: "<?php echo site_url('admin/method/send_telegram'); ?>",
		type: 'POST',
		data: {
			"Title": title
		},
		dataType: 'json',
		success: function(data) {
			alert(data.message);
		}
	});
}
</script>