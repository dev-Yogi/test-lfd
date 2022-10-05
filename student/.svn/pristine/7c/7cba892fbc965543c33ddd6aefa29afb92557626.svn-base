<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Reports <span class="text-muted small"><span class="p-2">&middot;</span> Student Completions</span>
				</h1>
			</div>
			<div class="row">
				<div class="col-12">
					<a href="<?php echo base_url("admin/report") ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left mr-1"></i>Back to Reports</a>
					<div class="card">
						<div class="card-header d-flex">
							<div>Student Completions</div>
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
							<a href="<?php echo base_url('admin/report/students_completion_lessons/csv/?' . $_SERVER['QUERY_STRING']) ?>" target="_blank" class="btn btn-secondary btn-sm ml-auto"><i class="fe fe-download mr-1"></i>Download as CSV</a>
						</div>
						<div class="table-responsive">
							<table class="table card-table table-vcenter table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Last Name</th>
										<th>First Name</th>
										<th>School</th>
										<th>Category</th>
										<th>Course</th>
										<th>Lesson</th>
										<th width="120">Lesson Started</th>
										<th width="120">Lesson Completed</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($completion_lessons as $completion): ?>
										<tr>
											<td><?php echo $completion->id ?></td>
											<td><?php echo $completion->last_name ?></td>
											<td><?php echo $completion->first_name ?></td>
											<td><?php echo $completion->school_name ?></td>
											<td><?php echo $completion->category ?></td>
											<td><?php echo $completion->course ?></td>
											<td><?php echo $completion->lesson ?></td>
											<td><?php echo $completion->lesson_started ? date('j M, Y', strtotime($completion->lesson_started)) : NULL ?></td>
											<td><?php echo $completion->lesson_completed ? date('j M, Y', strtotime($completion->lesson_completed)) : NULL ?></td>
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