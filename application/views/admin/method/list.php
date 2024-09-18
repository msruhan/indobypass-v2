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
				<a href="<?php echo site_url('admin/method/add') ?>"><button id="sample_editable_1_new" class="btn btn-success"> Add Service <i class="fa fa-plus"></i></button></a>
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
            { "data": "ID" },
            { "data": "Title" },
			{ "data": "Status" },
			{ "data": "Price" },
			{ "data": "CreatedDateTime" },
            { "data": "delete" }
		]
    } );

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