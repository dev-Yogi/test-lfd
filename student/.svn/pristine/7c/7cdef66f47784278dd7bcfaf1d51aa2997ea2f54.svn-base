<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Reports <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<div class="row">
				<?php echo alerts() ?>
				<div class="col-12">
					<a href="<?php echo base_url("admin/report") ?>" class="btn btn-secondary mb-3">Back to Reports</a>
					<div class="card">
						<div class="card-header d-flex">
							<div>Logins 30 Days</div>
							<div class="ml-auto">
								<form class="form-inline">
									<?php $formatted_start_date = ($start_date ?? null) ? date('m/d/Y', strtotime($start_date)) : null ?>
									<?php $formatted_stop_date = ($stop_date ?? null) ? date('m/d/Y', strtotime($stop_date)) : null ?>
									<div class="input-group <?php echo is_valid('start_date') ?>">
										<div class="input-group-prepend">
											<button type="button" class="btn btn-secondary btn-sm dropdown-toggle open-date-picker" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fe fe-calendar"></i>
											</button>
										</div>
										<input type="text" class="form-control form-control-sm date-picker <?php echo is_valid('start_date') ?>" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="MM/DD/YYYY" placeholder="MM/DD/YYYY" value="<?php echo set_value('start_date', $formatted_start_date) ?>">
										<?php echo form_error('start_date') ?>
									</div>
									<div class="mx-3">to</div>
									<div class="input-group <?php echo is_valid('start_date') ?>">
										<div class="input-group-prepend">
											<button type="button" class="btn btn-secondary btn-sm dropdown-toggle open-date-picker" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fe fe-calendar"></i>
											</button>
										</div>
										<input type="text" class="form-control form-control-sm date-picker <?php echo is_valid('stop_date') ?>" name="stop_date" data-inputmask-alias="datetime" data-inputmask-inputformat="MM/DD/YYYY" placeholder="MM/DD/YYYY" value="<?php echo set_value('stop_date', $formatted_stop_date) ?>">
										<?php echo form_error('stop_date') ?>
									</div>
									<input type="submit" class="btn btn-secondary btn-sm ml-3" value="Apply">
								</form>
							</div>
						</div>
						<div class="card-body">
							<div id="chart"></div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Logins 30 Days</h3>
                            <div class="pr-0">
                                <form class="input-icon">
                                    <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                    <div class="input-icon-addon">
                                        <i class="fe fe-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
						<table class="table card-table table-vcenter table-hover data-table-report-logins">
							<thead>
								<tr>
									<th>Number of Logins</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($logins as $row): ?>
									<tr>
										<td><?php echo $row->count ?></td>
										<td><?php echo $row->logged_in_at ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					<div class="data-table-pagination float-left"></div>
					<div class="data-table-records-info d-none"></div>
					<div class="data-table-show-per-page float-right"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var report = {
		x: <?php echo json_encode(array_column($logins, 'date')) ?>,
		y: <?php echo json_encode(array_column($logins, 'count')) ?>
	}
</script>
<?php $css = array(base_url('js/c3/c3.css')) ?>
<?php $js = array(
	base_url('js/c3/d3.js'), 
	base_url('js/c3/c3.js'),
	base_url('js/report.js')
) ?>
<?php $this->load->view('admin/footer', compact('css', 'js')) ?>