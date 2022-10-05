	    </div>
	</div>
    <footer class="footer mt-5">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-auto ml-lg-auto">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <!-- <li class="list-inline-item"><a href="<?php echo base_url('siteuse') ?>">Site Use Terms</a></li>
                                <li class="list-inline-item"><a href="<?php echo base_url('privacy') ?>">Privacy Policy</a></li> -->
                            </ul>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url('help/contact') ?>" class="btn btn-outline-primary btn-sm">Contact Us</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                    Copyright © 2020 <a href="<?php echo base_url() ?>">AIM Participant Portal</a> All rights reserved.
                </div>
                <div class="col-12 mt-3">
                    AIM TRiO Programs are a National Grants Funded by the U. S. Department of Education in the amount of $2,084,267 (FY 2019—2020)
                </div>
            </div>
        </div>
    </footer>
</main>
<script src="<?php echo base_url('js/bundle.js') ?>?v=12"></script>
<script src="<?php echo base_url('js/inputmask.js') ?>?v=12"></script>
<script src="<?php echo base_url('js/ckeditor/ckeditor.js') ?>?v=12"></script>
<script src="<?php echo base_url('js/bs-custom-file-input.js') ?>?v=12"></script>
<script src="<?php echo base_url('js/employer/employer.js') ?>?v=12"></script>
<script src="<?php echo base_url('js/student.js') ?>?v=12"></script>
<script>
   var base_url = '<?php echo base_url() ?>';
   var today = '22 Apr, 2020';
</script>

<?php if (!empty($js)): ?>
    <?php if (gettype($js) == 'array'): ?>
        <?php foreach ($js as $file): ?>
            <script src="<?php echo $file ?>?v=12"></script>
        <?php endforeach ?>
    <?php else: ?>
        <script src="<?php echo $js ?>?v=12"></script>
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
            <link rel="stylesheet" href="<?php echo $file ?>?v=12">
        <?php endforeach ?>
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo $css ?>?v=12">
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
                    height: 320,
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