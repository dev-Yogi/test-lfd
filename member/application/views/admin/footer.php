        <footer class="p-5 m-5"></footer>
        <script src="<?php echo base_url('js/jquery.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/jquery-ui.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/popper.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/bootstrap.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/moment.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/datatables.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/datatables-bootstrap.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/selectize.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/helper.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/inputmask.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/ckeditor/ckeditor.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/bs-custom-file-input.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/employer/employer.js') ?>?v=4"></script>
        <script src="<?php echo base_url('js/admin/admin-datatable.js') ?>?v=4"></script>
        <script>
            var base_url = '<?php echo base_url() ?>';
            var today = '<?php echo date('j M, Y') ?>';
        </script>

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