<?php $this->load->view('header') ?>
<div class="container">

    <div class="col-lg-6 offset-lg-3">

        <div class="page-header">
            <div>
                <h1 class="page-title">Offerings <small class="ml-2 text-muted"><?php echo $offering->title ?></small></h1>
            </div>
        </div>
        <a href="<?php echo base_url('offering/all') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Offerings</a>
        <?php echo alerts() ?>
        <div class="card p-5 mb-3">
            <?php $this->load->view('offering/form') ?>
        </div>
        <?php if (!empty($offering)): ?>
            <a href="<?php echo base_url("offering/remove/{$offering->id}") ?>" onclick="return confirm('Are you sure you want to remove this offering?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove offering</a>
        <?php endif ?>
    </div>
</div>

<?php 
$js = array(
    base_url('/js/selectize.js'),
    base_url('/js/offering-submit.js')
);
$css = array(
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css'
);
?>

<?php $this->load->view('footer', compact('js', 'css')) ?>