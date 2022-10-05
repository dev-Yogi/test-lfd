<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Contact <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<div class="card">
				<div class="card-body">
					Students may submit contact form requests via <a target="_blank" href="<?php echo base_url('help') ?>">Help</a> > <a target="_blank" href="<?php echo base_url('help/contact') ?>">Contact Form</a>.
				</div>
			</div>
			<?php echo alerts() ?>
			<div class="row">
				<div class="col">
					<div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Contact Form Submissions</h3>
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
							<table class="table card-table table-vcenter table-hover data-table-contact">
								<thead>
									<tr>
										<th>Submitted</th>
										<th>Name</th>
										<th>Message</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($submissions as $submission): ?>
										<tr>
											<td><?php echo $submission->created ?></td>
											<td><?php echo display_name($submission) ?></td>
											<td class="text-muted"><?php echo character_limiter($submission->message, 50) ?></td>
											<td class="text-right w-25">
												<a href="<?php echo base_url("admin/contact/view/{$submission->id}") ?>" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-eye"></i> View</a>
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