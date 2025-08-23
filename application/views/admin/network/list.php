<div class="row">
	<div class="col-md-12">
		<!-- BEGIN SAMPLE TABLE PORTLET-->
		<div class="portlet">
			
			<?php echo form_open('admin/network/add', array('method' => 'post', 'id'=>'form')); ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>Service Groups Manager
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-hover" cellpadding="0" cellspacing="0" width="100%" id="TableDeferLoading">
						<thead>
							<tr>                                        
<th width="5%">ID</th>
<th>Title</th>
<th width="20%">Updated Date Time</th>
<th width="20%">Created Date Time</th>                                         
<th width="7%">Options</th>
							</tr>
						</thead>
						<tfoot>
								<tr>
<th>ID</th>
<th>Title</th>
<th>Updated Date Time</th>
<th>Created Date Time</th>                                         
<th>Options</th>
								</tr>
						</tfoot>
					</table>
				</div>
				<a href="<?php echo site_url('admin/network/add') ?>"><button id="sample_editable_1_new" class="btn btn-success"> Add Group <i class="fa fa-plus"></i></button></a>
			</div>
			<?php echo form_close(); ?>
		</div>
		<!-- END SAMPLE TABLE PORTLET-->
	</div>
</div>

<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
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
	var table = $('#TableDeferLoading').DataTable( {
		"processing": true,
		"serverSide": true,
		"deferRender": true,
		"ajax": "<?php echo site_url('admin/network/listener'); ?>",
	   "order": [[ 0, 'desc' ]],
		"rowReorder": {
			dataSrc: 'ID',
			selector: 'tr',
			snapX: true
		},
		"columnDefs": [
			{ "orderable": false, "targets": [-1] },
			{ "searchable": false, "targets": [-1] }
		],
		"columns": [
			{ "data": "ID" },
			{ "data": "Title" },
			{ "data": "CreatedDateTime" },
			{ "data": "UpdatedDateTime" },
			{ "data": "delete" }
		]
	});

	table.on('row-reorder', function(e, diff, edit) {
		if(diff.length === 0) return;
		var id1 = diff[0].oldData;
		var id2 = diff[0].newData;
		if(!id1 || !id2 || id1 === id2) return;
		$.ajax({
			url: '<?php echo site_url('admin/network/swap_id'); ?>',
			method: 'POST',
			data: { id1: id1, id2: id2 },
			success: function(res) {
				table.ajax.reload();
			},
			error: function(){ alert('Swap gagal!'); }
		});
	});

	// Modal HTML
	var swapModal = $('<div id="swapModal" class="modal" tabindex="-1" role="dialog" style="display:none;">\
	  <div class="modal-dialog" role="document">\
		<div class="modal-content">\
		  <div class="modal-header"><h5 class="modal-title">Swap ID Group</h5></div>\
		  <div class="modal-body">\
			<label>Pilih ID group yang ingin ditukar:</label>\
			<select id="swapTargetId" class="form-control"></select>\
		  </div>\
		  <div class="modal-footer">\
			<button type="button" class="btn btn-primary" id="doSwapBtn">Tukar</button>\
			<button type="button" class="btn btn-secondary" id="closeSwapModal">Batal</button>\
		  </div>\
		</div>\
	  </div>\
	</div>');
	$('body').append(swapModal);

	// Event tombol swap
	$('#TableDeferLoading').on('click', '.swap-id-btn', function() {
		var currentId = $(this).data('id');
		var table = $('#TableDeferLoading').DataTable();
		var allData = table.rows().data().toArray();
		var select = $('#swapTargetId');
		select.empty();
		allData.forEach(function(row) {
			if(row.ID != currentId) {
				select.append('<option value="'+row.ID+'">'+row.ID+' - '+row.Title+'</option>');
			}
		});
		swapModal.data('currentId', currentId);
		swapModal.show();
	});
	$('#closeSwapModal').on('click', function(){ swapModal.hide(); });
	$('#doSwapBtn').on('click', function(){
		var id1 = swapModal.data('currentId');
		var id2 = $('#swapTargetId').val();
		if(!id2) return;
		$.ajax({
			url: '<?php echo site_url('admin/network/swap_id'); ?>',
			method: 'POST',
			data: { id1: id1, id2: id2 },
			success: function(res) {
				swapModal.hide();
				$('#TableDeferLoading').DataTable().ajax.reload();
			},
			error: function(){ alert('Swap gagal!'); }
		});
	});
} );		
</script>