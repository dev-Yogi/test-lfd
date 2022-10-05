
		<script>
			var base_url = '<?php echo base_url() ?>';
		</script>

		<script src="<?php echo base_url('js/jquery.js') ?>"></script>
		<script src="<?php echo base_url('js/jquery-ui.js') ?>"></script>
		<script src="<?php echo base_url('js/popper.js') ?>"></script>
		<script src="<?php echo base_url('js/bootstrap.js') ?>"></script>
		<script src="<?php echo base_url('js/datatables.js') ?>"></script>
		<script src="<?php echo base_url('js/datatables-bootstrap.js') ?>"></script>
		<script src="<?php echo base_url('js/selectize.js') ?>"></script>
		<script src="<?php echo base_url('js/helper.js') ?>"></script>
		<script src="<?php echo base_url('js/inputmask.js') ?>"></script>
		<script src="<?php echo base_url('js/ckeditor/ckeditor.js') ?>"></script>
		<script src="<?php echo base_url('js/bs-custom-file-input.js') ?>"></script>
		<script src="<?php echo base_url('js/employer/employer.js') ?>"></script>
		
		<?php if (!empty($js)): ?>
			<?php if (gettype($js) == 'array'): ?>
				<?php foreach ($js as $file): ?>
					<script src="<?php echo $file ?>"></script>
				<?php endforeach ?>
			<?php else: ?>
				<script src="<?php echo $js ?>"></script>
			<?php endif ?>
		<?php endif ?>
		<?php if (!empty($script)): ?>
			<script><?php echo $script ?></script>
		<?php endif ?>

		<?php if (!empty($style)): ?>
			<style><?php echo $style ?></style>
		<?php endif ?>
		<?php if (!empty($css)): ?>
			<?php if (gettype($css) == 'array'): ?>
				<?php foreach ($css as $file): ?>
					<link rel="stylesheet" href="<?php echo $file ?>">
				<?php endforeach ?>
			<?php else: ?>
				<link rel="stylesheet" href="<?php echo $css ?>">
			<?php endif ?>
		<?php endif ?>
	</body>
</html>