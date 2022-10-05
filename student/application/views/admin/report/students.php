<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Reports <span class="text-muted small"><span class="p-2">&middot;</span> Students</span>
				</h1>
			</div>
			<div class="row">
				<div class="col-xl-6">
					<a href="<?php echo base_url("admin/report") ?>" class="btn btn-secondary mb-3">Back to Reports</a>
					<?php echo alerts() ?>
					<!-- <div class="card card-collapse card-collapsed">
						<div class="card-header">
							Filters
							<div class="card-options">
								<a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
							</div>
						</div>
						<div class="card-body">
							<form>
								<table class="table table-borderless">
									<input type="hidden" name="search" value="1">
									<tr>
										<td>Name</td>
										<td>
											<input type="text" name="name" value="<?php echo $filters['name'] ?>" class="form-control form-control-sm w-50">
										</td>
									</tr>
									<tr>
										<td>School</td>
										<td>
											<select class="form-control form-control-sm w-50" name="category_id" disabled>
												<option disabled selected>Select a School</option>
												<?php foreach ($schools as $school): ?>
													<option value="<?php echo $school->id ?>" <?php echo ($school->id == $filters['school_id']) ? 'selected' : null ?>><?php echo $school->name ?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Lessons</td>
										<td>
											<div class="form-inline">
												<select class="form-control form-control-sm w-25 mr-2" disabled name="lesson_count_mode">
													<option>Under</option>
													<option>Over</option>
												</select>
												<input type="text" disabled name="lesson_count" class="form-control form-control-sm w-25">
											</div>
										</td>
									</tr>
									<tr>
										<td>Created</td>
										<td>
											<div class="form-inline">
												<input type="text" name="created_start" class="form-control form-control-sm date-picker" data-mask="00/00/0000" data-mask-clearifnotmatch="true" placeholder="MM/DD/YYYY" autocomplete="off" maxlength="10" value="<?php echo $filters['created_start'] ? date('m/d/Y', strtotime($filters['created_start'])) : null ?>" name="created_start">
												<span class="mx-2">to</span>
												<input type="text" name="created_stop" class="form-control form-control-sm date-picker" data-mask="00/00/0000" data-mask-clearifnotmatch="true" placeholder="MM/DD/YYYY" autocomplete="off" maxlength="10" value="<?php echo $filters['created_stop'] ? date('m/d/Y', strtotime($filters['created_stop'])) : null ?>" name="created_stop">
											</div>
										</td>
									</tr>
									<tr>
										<td>Last Login</td>
										<td>
											<div class="form-inline">
												<input type="text" name="last_login_start" class="form-control form-control-sm date-picker" data-mask="00/00/0000" data-mask-clearifnotmatch="true" placeholder="MM/DD/YYYY" autocomplete="off" maxlength="10" value="<?php echo $filters['last_login_start'] ? date('m/d/Y', strtotime($filters['last_login_start'])) : null ?>" name="last_login_start">
												<span class="mx-2">to</span>
												<input type="text" name="last_login_stop" class="form-control form-control-sm date-picker" data-mask="00/00/0000" data-mask-clearifnotmatch="true" placeholder="MM/DD/YYYY" autocomplete="off" maxlength="10" value="<?php echo $filters['last_login_stop'] ? date('m/d/Y', strtotime($filters['last_login_stop'])) : null ?>" name="last_login_stop">
											</div>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><button class="btn btn-primary btn-sm">Apply</button></td>
									</tr>
								</table>
							</form>
						</div>
					</div> -->
				</div>
				<div class="col-12">
					<div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Students</h3>
                            <div class="pr-0">
                                <form class="input-icon">
                                    <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                    <div class="input-icon-addon">
                                        <i class="fe fe-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
						<div class="table-responsive">
							<table class="table card-table table-vcenter table-hover data-table-report-students">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>School</th>
										<th>Courses</th>
										<th>Lessons</th>
										<th>Created</th>
										<th>Last Login</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $student): ?>
										<tr>
											<td><?php echo $student->id ?></td>
											<td><?php echo display_name($student) ?></td>
											<td><?php echo $student->school_name ?></td>
											<td><?php echo $student->count_courses ?? '<span class="text-muted">0</span>' ?></td>
											<td><?php echo $student->count_lessons ?? '<span class="text-muted">0</span>' ?></td>
											<td><?php echo $student->created ?></td>
											<td><?php echo $student->last_login ?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="data-table-pagination float-left"></div>
					<div class="data-table-records-info d-none"></div>
					<div class="data-table-show-per-page float-right"></div>
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