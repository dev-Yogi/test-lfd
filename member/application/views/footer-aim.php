
		
		<?php 
		    // This may seem obscure - the header was required to be hosted elsewhere to keep the same header for all CI & WP instances.
		    // Our local stylesheets then has to be defined after this header import to keep the CSS overrides in correct order
		    if (ENVIRONMENT == 'development') {
		        // include 'C:\xampp\htdocs\include-footer.php';
		        include 'C:\xampp\htdocs\member\application\views\include-footer.php';
		    } else {
		        include '/aim/exports/www/aiminstitute.org/include-footer.php';
		    }
		?>

		<script src="<?php echo base_url('js/moment.js') ?>?v=4"></script>
		<script src="<?php echo base_url('js/datatables.js') ?>?v=4"></script>
		<script src="<?php echo base_url('js/datatables-bootstrap.js') ?>?v=4"></script>
		<script src="<?php echo base_url('js/selectize.js') ?>?v=4"></script>
		<script src="<?php echo base_url('js/helper.js') ?>?v=4"></script>
		<script src="<?php echo base_url('js/inputmask.js') ?>?v=4"></script>
		
		<?php if (!empty($js)) : ?>
		    <?php if (gettype($js) == 'array') : ?>
		        <?php foreach ($js as $file) : ?>
		            <script src="<?php echo $file ?>?v=4"></script>
		        <?php endforeach ?>
		    <?php else : ?>
		        <script src="<?php echo $js ?>?v=4"></script>
		    <?php endif ?>
		<?php endif ?>
		<?php if (!empty($script)) : ?>
		    <script>
		        <?php echo $script ?>
		    </script>
		<?php endif ?>

		<?php if (!empty($style)) : ?>
		    <style>
		        <?php echo $style ?>
		    </style>
		<?php endif ?>
		<?php if (!empty($css)) : ?>
		    <?php if (gettype($css) == 'array') : ?>
		        <?php foreach ($css as $file) : ?>
		            <link rel="stylesheet" href="<?php echo $file ?>">
		        <?php endforeach ?>
		    <?php else : ?>
		        <link rel="stylesheet" href="<?php echo $css ?>">
		    <?php endif ?>
		<?php endif ?>
	</body>
</html>