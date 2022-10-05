<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Students <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<?php if (is_staff()): ?>
				<a href="<?php echo base_url('admin/student/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Student</a>
				<a href="<?php echo base_url('admin/instructor') ?>" class="btn btn-secondary mb-5 ml-3"> Manage Instructors</a>
			<?php endif ?>
			<div class="row">
				<div class="col">
					<?php echo alerts() ?>
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
							<table class="table card-table table-vcenter table-hover data-table-students">
								<thead>
									<tr>
										<th width="10">ID</th>
										<th width="324">Name</th>
										<th width="262"><?php echo $extra_field_column ?></th>
										<th width="162">Created</th>
										<th width="162">Last Login</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $student): ?>
										<tr>
											<td class="text-muted"><?php echo $student->id ?></td>
											<td><a href="<?php echo base_url("admin/student/view/{$student->id}") ?>"><?php echo display_name($student) ?></a></td>
											<td><?php echo $student->extra_field_value ?></td>
											<td><?php echo $student->created ?></td>
											<td><?php echo $student->last_login ?></td>
											<td class="text-right">
												<a href="<?php echo base_url("admin/student/view/{$student->id}") ?>" class="btn btn-secondary btn-sm mr-1"><i class="fe fe-eye"></i> View</a><a href="<?php echo base_url("admin/student/edit/{$student->id}") ?>" class="btn btn-secondary btn-sm <?php echo is_staff() ? null : 'disabled' ?>"><i class="fe fe-edit-2"></i> Edit</a>
											</td>
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
<?php $this->load->view('admin/footer') ?>