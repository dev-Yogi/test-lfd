<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Courses <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/course/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Course</a>
			<a href="<?php echo base_url('admin/category/all') ?>" class="btn btn-secondary mb-5 ml-3"> Manage Categories</a>
			<div class="row">
				<div class="col">
                    <?php echo alerts() ?>
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
							<table class="table card-table table-vcenter table-hover data-table data-table-courses">
								<thead>
									<tr>
										<th width="445">Course</th>
										<th width="211">Category</th>
										<th width="248">Instructor</th>
										<th width="120">Lessons</th>
										<th width="87">Status</th>
										<th width="150">Created</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($courses as $course): ?>
										<tr>
											<td><a href="<?php echo base_url("admin/course/view/{$course->id}") ?>"><?php echo $course->name ?></a></td>
											<td><?php echo implode(", ", array_map(function ($item) { return "$item->name"; }, $course->categories)) ?></td>
											<td><?php echo implode(", ", array_map(function ($item) { return "$item->instructor_first_name $item->instructor_last_name"; }, $course->instructors)) ?></td>
											<td class="text-muted"><?php echo $course->lessons ?? 0 ?></td>
											<td><?php echo ucwords($course->status) ?></td>
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
<?php $this->load->view('admin/footer') ?>