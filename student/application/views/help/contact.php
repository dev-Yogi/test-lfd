<?php $this->load->view('header') ?>
<div class="container">
	<div class="page-header">
		<h1 class="page-title">
			Contact Us
		</h1>
	</div>
	<?php echo alerts() ?>
	<div class="card p-5 help">
		<div class="card-body">
			<form method="post">
				<div class="form-group">
					<label class="form-label">Your Name</label>
					<div class="form-control-plaintext"><?php echo $this->member->first_name ?></div>
				</div>
				<div class="form-group">
					<label class="form-label">Your Email</label>
					<div class="form-control-plaintext"><?php echo $this->member->email ?></div>
				</div>
				<div class="form-group">
					<label class="form-label">Message</label>
					<textarea class="form-control <?php echo is_valid('message') ?>" name="message" rows="6" ><?php echo set_value('message') ?></textarea>
					<?php echo form_error('message') ?>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Send">
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('footer') ?>