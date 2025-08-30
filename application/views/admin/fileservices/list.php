<div class="row">
	<!-- <div class="col-md-12">

		<div class="portlet">
				
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>File Services
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-hover" cellpadding="0" cellspacing="0" width="100%" id="TableDeferLoading">
						<thead>
							<tr>
								<th width="5%">ID</th>
								<th width="50%" >Title</th>                            
								<th width="5%">Price</th>
								<th width="10%">Status</th>
								<th width="15%">Created Date Time</th>
								<th width="7%">Options</th>
							</tr>
						</thead>
					</table>
				</div>
				<a href="<?php echo site_url('admin/fileservices/add') ?>"><button id="sample_editable_1_new" class="btn btn-success">Add File Service <i class="fa fa-plus"></i></button></a>
			</div>
		</div>

	</div> -->


<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-list"></i> Log Aktivitas
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="TableLogAktivitas">
						<thead>
							<tr>
								<th>No</th>
								<th>User</th>
								<th>Aktivitas</th>
								<th>Negara</th>
								<th>IP Address</th>
								<th>Waktu</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($logs)) : $no=1; foreach ($logs as $log) : ?>
							<?php
								$countryCode = '';
								$countryName = '';
								if (!empty($log->ip_address)) {
									$ip = $log->ip_address;
									$cacheKey = 'flag_' . md5($ip);
									$flagCache = isset($_SESSION[$cacheKey]) ? $_SESSION[$cacheKey] : null;
									if ($flagCache) {
										list($countryCode, $countryName) = explode('|', $flagCache);
									} else {
										$json = @file_get_contents('http://ip-api.com/json/' . $ip . '?fields=countryCode,country');
										if ($json) {
											$data = json_decode($json, true);
											if (!empty($data['countryCode'])) {
												$countryCode = strtolower($data['countryCode']);
												$countryName = $data['country'];
												$_SESSION[$cacheKey] = $countryCode . '|' . $countryName;
											}
										}
									}
								}
							?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= htmlspecialchars($log->username) ?></td>
								<td><?= htmlspecialchars($log->activity) ?></td>
								<td>
									<?php if ($countryCode): ?>
										<img src="https://flagcdn.com/16x12/<?= $countryCode ?>.png" alt="<?= htmlspecialchars($countryName) ?>" title="<?= htmlspecialchars($countryName) ?>" style="margin-right:4px;"> <?= htmlspecialchars($countryName) ?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td><?= htmlspecialchars($log->ip_address) ?></td>
								<td><?= htmlspecialchars($log->created_at) ?></td>
							</tr>
							<?php endforeach; else: ?>
							<tr><td colspan="6" class="text-center">Tidak ada data log.</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<style>
  /* Agar pagination sejajar info entries */
  .dataTables_wrapper .dataTables_paginate {
	float: right;
	margin-top: -32px;
  }
  .dataTables_wrapper .dataTables_info {
	float: left;
	margin-top: 8px;
  }
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('#TableLogAktivitas').DataTable({
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"pageLength": 10,
		"lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
		// dom: l - length, f - filter, r - processing, t - table, i - info, p - pagination
		"dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
		"language": {
			"paginate": {
				"previous": "<span class='btn btn-default btn-sm'>&laquo; Previous</span>",
				"next": "<span class='btn btn-default btn-sm'>Next &raquo;</span>"
			}
		}
	});
});
</script>