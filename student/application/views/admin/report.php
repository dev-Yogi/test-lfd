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
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label class="form-label">View Data On ...</label>
								<div class="input-group">
									<select class="form-control" id="report-dropdown">
										<option value="courses">Courses</option>
										<option value="students">Students</option>
										<option value="activity">Student Activity</option>
										<option value="students_completion_lessons">Student Lesson Completions</option>
										<!-- <option value="instructors">Instructors</option> -->
									</select>
									<span class="input-group-append">
										<a class="btn btn-primary text-light" onclick="javascript:redirectReport()" type="button">Go <i class="fe fe-chevron-right"></i></a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card">
						<div class="card-header d-flex">
							<div>Logins 30 Days</div>
							<a href="<?php echo base_url("admin/report/logins") ?>" class="btn btn-secondary btn-sm ml-auto">View Data <i class="fe fe-chevron-right"></i></a>
						</div>
						<div class="card-body">
							<div id="chart_logins"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card">
						<div class="card-header d-flex">
							<div>Course Completions</div>
							<a href="<?php echo base_url("admin/report/activity") ?>" class="btn btn-secondary btn-sm ml-auto">View Data <i class="fe fe-chevron-right"></i></a>
						</div>
						<div class="card-body">
							<div id="chart_course_completions"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card">
						<div class="card-header d-flex">
							<div>Lesson Completions</div>
							<a href="<?php echo base_url("admin/report/activity") ?>" class="btn btn-secondary btn-sm ml-auto">View Data <i class="fe fe-chevron-right"></i></a>
						</div>
						<div class="card-body">
							<div id="chart_lesson_completions"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<script>
	var home = true;
	var report_logins = {
		x: <?php echo json_encode(array_column($data['report_logins'], 'date')) ?>,
		y: <?php echo json_encode(array_column($data['report_logins'], 'count')) ?>
	}
	var report_course_completions = {
		x: <?php echo json_encode(array_column($data['report_course_completions'], 'date')) ?>,
		y: <?php echo json_encode(array_column($data['report_course_completions'], 'count')) ?>
	}
	var report_lesson_completions = {
		x: <?php echo json_encode(array_column($data['report_lesson_completions'], 'date')) ?>,
		y: <?php echo json_encode(array_column($data['report_lesson_completions'], 'count')) ?>
	}
</script>
<?php $css = array(base_url('js/c3/c3.css')) ?>
<?php $js = array(
	base_url('js/c3/d3.js'), 
	base_url('js/c3/c3.js'),
	base_url('js/report.js')
) ?>
<?php $this->load->view('admin/footer', compact('css', 'js')) ?>