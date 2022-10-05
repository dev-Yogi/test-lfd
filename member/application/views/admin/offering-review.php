<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">Review Offering</h1>
                    </div>
                    <a href="<?php echo base_url('admin/offering/queue') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Offering Queue</a>
                    <?php echo alerts() ?>
                    <div class="card p-5 mb-3">
                        <?php $this->load->view('offering/form') ?>
                    </div>

                    <?php if (!empty($offering)): ?>
                        <!-- <a href="<?php echo base_url("admin/offering/remove/{$offering->id}") ?>" onclick="return confirm('Are you sure you want to remove this offering?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove offering</a> -->
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$js = array(
    'https://www.google.com/recaptcha/api.js', 
    base_url('/js/selectize.js'),
    base_url('/js/offering-submit.js')
);
$css = array(
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css'
);
?>

<?php $this->load->view('admin/footer', compact('js', 'css')) ?>
