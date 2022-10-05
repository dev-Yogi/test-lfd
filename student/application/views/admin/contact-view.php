<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Contact <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/contact') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left mr-2"></i>Back to Submissions</a>
			<?php echo alerts() ?>
			<div class="row">
				<div class="col">
						<div class="card">
							<div class="card-body">
								<div class="form-group">
									<label class="form-label">Submitted</label>
									<div><?php echo date('j M, Y H:iA', strtotime($submission->created)) ?></div>
								</div>
								<div class="form-group">
									<label class="form-label">Submitted By</label>
									<div><?php echo $submission->first_name . " " . $submission->last_name ?></div>
								</div>
								<div class="form-group">
									<label class="form-label">Submitter Email</label>
									<div><?php echo $submission->email ?></div>
								</div>
								<div class="form-group">
									<label class="form-label">Message</label>
									<div><?php echo nl2br($submission->message) ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/footer') ?>