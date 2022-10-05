<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Dashboard
				</h1>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<a href="<?php echo base_url() ?>" class="btn btn-secondary"><i class="fe fe-corner-up-left mr-2"></i>Go to Participant Portal</a>
					<a href="/member" class="btn btn-secondary float-right">Go to AIM Platform<i class="fe fe-arrow-right ml-2"></i></a>
					<hr>
					<?php echo alerts() ?>
				</div>
				<div class="col-lg-6">
					<div class="page-header">
						Instructor Panel
					</div>
					<div class="card p-5">
						<a href="<?php echo base_url('admin/course/create') ?>" class="btn btn-primary text-left mb-3"><i class="fe fe-plus mr-2"></i> Set up new course</a>
						<a href="<?php echo base_url('admin/course/me') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-list mr-2"></i> My Courses</a>
					</div>
				</div>

				<div class="col-lg-6">
					<?php if (is_staff()): ?>
						<div class="page-header">
							Admin Panel
						</div>
						<div class="card p-5">
							<a href="<?php echo base_url('admin/student/create') ?>" class="btn btn-primary text-left mb-3"><i class="fe fe-plus mr-2"></i> Set up new student</a>
							<a href="<?php echo base_url('admin/instructor/create') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-user mr-2"></i> Add New Instructor <i class="fe fe-chevron-right float-right mt-1"></i></a>
							<a href="<?php echo base_url('admin/instructor') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-user mr-2"></i> Manage Instructors <i class="fe fe-chevron-right float-right mt-1"></i></a>
							<a href="<?php echo base_url('admin/contact') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-message-circle mr-2"></i> Contact Form Submissions <i class="fe fe-chevron-right float-right mt-1"></i></a>
							<a href="<?php echo base_url('admin/settings') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-settings mr-2"></i> Program Settings <i class="fe fe-chevron-right float-right mt-1"></i></a>
						</div>
					<?php endif ?>
				</div>

				<div class="col-lg-6">
					<div class="page-header">
						Recent Students
					</div>

					<div class="card">
						<div class="table-responsive">
							<table class="table card-table table-vcenter table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th width="120">School</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $student): ?>
										<tr>
											<td>
												<a href="<?php echo base_url("admin/student/view/{$student->id}") ?>"><?php echo display_name($student) ?></a>
											</td>
											<td width="240"><?php echo $student->school_name ?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
							<div class="card-footer text-right no-bg">
								<a href="<?php echo base_url("admin/student") ?>" class="btn btn-secondary btn-sm">View Students »</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/footer') ?>