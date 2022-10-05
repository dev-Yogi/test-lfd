<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Programs <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/program/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Program</a>
			<div class="row">
				<div class="col">
                    <?php echo alerts() ?>
					<div class="card">
						<div class="table-responsive">
							<table class="table card-table table-vcenter table-hover data-table-programs">
								<thead>
									<tr>
										<th width="445">Name</th>
										<th width="150">Created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($programs as $program): ?>
										<tr>
											<td>
												<a href="<?php echo base_url("admin/dashboard/switch_program/{$program->id}") ?>" class="text-decoration-none">
													<?php echo $program->name ?>
													<div class="small text-muted"><?php echo $program->description ?></div>
												</a>
											</td>
											<td>
												<span class="date-for-sort d-none"><?php echo $program->created ?></span>
												<?php echo date('j M, Y', strtotime($program->created)) ?>
											</td>
											<td class="text-right">
												<a href="<?php echo base_url("admin/program/edit/{$program->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
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
</div>
<?php 
$this->load->view('admin/footer');
?>