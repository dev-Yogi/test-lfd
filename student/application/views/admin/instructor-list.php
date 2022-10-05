<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Instructors <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/instructor/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Instructor</a>
			<a href="<?php echo base_url('admin/student') ?>" class="btn btn-secondary mb-5 ml-3"> Manage Students</a>
			<div class="row">
				<div class="col">
					<?php echo alerts() ?>
					<div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Instructors</h3>
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
							<table class="table card-table table-vcenter table-hover data-table-instructors">
								<thead>
									<tr>
										<th width="10">ID</th>
										<th width="384">Name</th>
										<th width="162">Created</th>
										<th width="162">Last Login</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($instructors as $instructor): ?>
										<tr>
											<td class="text-muted"><?php echo $instructor->id ?></td>
											<td><?php echo display_name($instructor) ?></td>
											<td><?php echo $instructor->created ?></td>
											<td><?php echo $instructor->last_login ?></td>
											<td class="text-right">
												<a href="<?php echo base_url("admin/instructor/edit/{$instructor->id}") ?>" class="btn btn-secondary btn-sm <?php echo is_staff() ? null : 'disabled' ?>"><i class="fe fe-edit-2"></i> Edit</a>
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