    <footer class="p-5 m-5"></footer>
    <script src="<?php echo base_url('js/jquery.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/jquery-ui.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/popper.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/bootstrap.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/moment.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/datatables.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/datatables-bootstrap.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/selectize.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/helper.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/inputmask.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/ckeditor/ckeditor.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/bs-custom-file-input.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/employer/employer.js') ?>?v=10"></script>
    <script src="<?php echo base_url('js/admin-datatable.js') ?>?v=10"></script>

    <?php if (!empty($js)): ?>
        <?php if (gettype($js) == 'array'): ?>
            <?php foreach ($js as $file): ?>
                <script src="<?php echo $file ?>?v=10"></script>
            <?php endforeach ?>
        <?php else: ?>
            <script src="<?php echo $js ?>?v=10"></script>
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
                <link rel="stylesheet" href="<?php echo $file ?>?v=10">
            <?php endforeach ?>
        <?php else: ?>
            <link rel="stylesheet" href="<?php echo $css ?>?v=10">
        <?php endif ?>
    <?php endif ?>
    
    <script>

        var base_url = '<?php echo base_url() ?>';
        $(document).ready(function() {
            // Description WSIWYG
            if($('#content_ckeditor').length) {
                ckeditor = CKEDITOR.replace( 'content_ckeditor', {
                    customConfig: base_url + 'js/ckeditor/careerlink-config.js',
                    toolbar: 'ClinkToolbarSource',
                    height: 700,
                    resize_minWidth: 620,
                    resize_maxWidth: 873
                });
                ckeditor.on('change',function(){
                    $('[name=content]').val(ckeditor.getData());
                    $('[name=content]').trigger('change');
                });
            }

            bsCustomFileInput.init()
        });
        var today = '24 Apr, 2020';

    </script>

</body>
</html>