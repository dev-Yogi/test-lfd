<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Settings
				</h1>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header">
						Instructor Panel
					</div>
					<div class="card p-5">
						<a href="<?php echo base_url('admin/settings/field') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-edit mr-2"></i> Student Form Fields</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/footer') ?>