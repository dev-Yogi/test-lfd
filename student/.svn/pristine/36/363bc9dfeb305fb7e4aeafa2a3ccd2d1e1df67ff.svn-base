<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Reports <span class="text-muted small"><span class="p-2">&middot;</span> Courses</span>
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
										<td>Course Name</td>
										<td>
											<input type="text" name="name" value="<?php echo $filters['name'] ?>" class="form-control form-control-sm w-50">
										</td>
									</tr>
									<tr>
										<td>Category</td>
										<td>
											<select class="form-control form-control-sm w-50" name="category_id">
												<option disabled selected>Select a Category</option>
												<?php foreach ($categories as $category): ?>
													<option value="<?php echo $category->id ?>" <?php echo ($category->id == $filters['category_id']) ? 'selected' : null ?>><?php echo $category->name ?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Instructor</td>
										<td>
											<select class="form-control form-control-sm w-50" name="instructor_id">
												<option disabled selected>Select an Instructor</option>
												<?php foreach ($instructors as $instructor): ?>
													<option value="<?php echo $instructor->id ?>" <?php echo ($instructor->id == $filters['instructor_id']) ? 'selected' : null ?>><?php echo display_name($instructor) ?></option>
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
										<td>Students</td>
										<td>
											<div class="form-inline">
												<select class="form-control form-control-sm w-25 mr-2" disabled name="student_count_mode">
													<option>Under</option>
													<option>Over</option>
												</select>
												<input type="text" disabled name="student_count" class="form-control form-control-sm w-25">
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
                            <h3 class="card-title mr-auto">Courses</h3>
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
							<table class="table card-table table-vcenter table-hover data-table-report-courses">
								<thead>
									<tr>
										<th>ID</th>
										<th>Course</th>
										<th>Category</th>
										<th>Instructor</th>
										<th class="text-center">Lessons</th>
										<th class="text-center">Started</th>
										<th class="text-center">Completed</th>
										<th>Created</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($courses as $course): ?>
										<tr>
											<td><?php echo $course->id ?></td>
											<td><?php echo $course->name ?></td>
											<td><?php echo implode(", ", array_map(function ($item) { return "$item->name"; }, $course->categories)) ?></td>
											<td><?php echo implode(", ", array_map(function ($item) { return "$item->instructor_first_name $item->instructor_last_name"; }, $course->instructors)) ?></td>
											<td class="text-center"><?php echo $course->lessons ?? 0 ?></td>
											<td class="text-center"><?php echo $course->students_started ?? 0 ?></td>
											<td class="text-center"><?php echo $course->students_complete ?? 0 ?></td>
											<td><?php echo $course->created ?></td>
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