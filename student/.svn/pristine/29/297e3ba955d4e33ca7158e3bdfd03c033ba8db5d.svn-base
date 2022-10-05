<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Reports <span class="text-muted small"><span class="p-2">&middot;</span> Student Activity</span>
				</h1>
			</div>
			<div class="row">
				<?php echo alerts() ?>
				<div class="col-12">
					<a href="<?php echo base_url("admin/report") ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left mr-1"></i>Back to Reports</a>
					<div class="card">
						<div class="card-header d-flex">
							<div>Student Activity</div>
							<form class="form-inline ml-auto">
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
							<a href="<?php echo base_url("admin/report/activity/csv/?" . $_SERVER['QUERY_STRING']) ?>" target="_blank" class="btn btn-secondary btn-sm ml-auto"><i class="fe fe-download mr-1"></i>Download as CSV</a>
						</div>
						<div class="table-responsive">
							<table class="table card-table table-vcenter table-hover">
								<thead>
									<tr>
										<th>Date</th>
										<th>Lessons Started</th>
										<th>Lessons Completed</th>
										<th>Assignments Submitted</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($data as $date => $row): ?>
										<tr>
											<td><?php echo $date ?></td>
											<td class="<?php echo $row['count_lesson_starts'] ? '' : 'text-muted' ?>"><?php echo $row['count_lesson_starts'] ?></td>
											<td class="<?php echo $row['count_lesson_completions'] ? '' : 'text-muted' ?>"><?php echo $row['count_lesson_completions'] ?></td>
											<td class="<?php echo $row['count_assignment_submissions'] ? '' : 'text-muted' ?>"><?php echo $row['count_assignment_submissions'] ?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php $script = '
$(\'[data-toggle="card-collapse"]\').on(\'click\', function(e) {
	let $card = $(this).closest(\'div.card\');

	$card.toggleClass(\'card-collapsed\');

	e.preventDefault();
	return false;
	});
	' ?>
<?php $this->load->view('admin/footer', compact('script')) ?>