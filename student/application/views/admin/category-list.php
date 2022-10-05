<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Categories <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/category/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Category</a>
            <a href="<?php echo base_url('admin/course') ?>" class="btn btn-secondary mb-5 ml-3"><i class="fe fe-chevron-left"></i>  Back to Courses</a>
			<div class="row">
				<div class="col">
                    <?php echo alerts() ?>
					<div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Course Categories</h3>
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
							<table class="table card-table table-vcenter table-hover data-table data-table-course-categories">
								<thead>
									<tr>
										<th>ID</th>
										<th>Category</th>
										<th>Created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($categories as $category): ?>
										<tr>
											<td class="text-muted"><?php echo $category->id ?></td>
											<td><?php echo $category->name ?></td>
											<td><?php echo $category->created ?></td>
											<td class="text-right">
												<a href="<?php echo base_url("admin/category/edit/{$category->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
											</td>
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
</div>
<?php $this->load->view('admin/footer') ?>