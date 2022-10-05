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
					<div class="card">
						<div class="card-header d-flex">
							<div>Logins 30 Days</div>
							<a href="<?php echo base_url("admin/report/view/logins") ?>" class="btn btn-secondary btn-sm ml-auto">View Data <i class="fe fe-chevron-right"></i></a>
						</div>
						<div class="card-body">
							<div id="chart_logins"></div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card">
						<div class="card-header d-flex">
							<div>Logins 30 Days</div>
							<a href="<?php echo base_url("admin/report/view/logins") ?>" class="btn btn-secondary btn-sm ml-auto">View Data <i class="fe fe-chevron-right"></i></a>
						</div>
						<table class="table card-table table-vcenter table-hover">
							<thead>
								<tr>
									<th>Course</th>
									<th>Category</th>
									<th>Instructor</th>
									<th>Lessons</th>
									<th>Created</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($courses as $course): ?>
									<tr>
										<td><?php echo $course->name ?></td>
										<td><?php echo $course->category_name ?></td>
										<td><?php echo $course->instructor_last_name ?>, <?php echo $course->instructor_first_name ?></td>
										<td class="text-muted"><?php echo $course->lessons ?></td>
										<td><?php echo date('j M, Y', strtotime($course->created)) ?></td>
										<td class="text-right">
											<a href="<?php echo base_url("admin/course/view/{$course->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
										</td>
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
<script>
	var report = {
		x: <?php echo json_encode(array_column($report, 'date')) ?>,
		y: <?php echo json_encode(array_column($report, 'count')) ?>
	}
</script>
<?php $css = array(base_url('js/c3/c3.css')) ?>
<?php $js = array(
	base_url('js/c3/d3.js'), 
	base_url('js/c3/c3.js'),
	base_url('js/report.js')
) ?>
<?php $this->load->view('admin/footer', compact('css', 'js')) ?>