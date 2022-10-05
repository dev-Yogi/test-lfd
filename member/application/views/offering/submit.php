<?php $this->load->view('header-sya') ?>
<div class="page offering offering-form">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1>Submit An Offering</h1>
                        <p>View various offerings on the STEM Community Platform - In partnership with Omaha STEM Ecosystem and AIM Institute.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 pb-5 mb-5">
                        <div class="text-center mt-5">
                            <a href="<?php echo base_url('offering') ?>" class="btn btn-sm mb-5"><i class="fa fa-chevron-left mr-2" aria-hidden="true"></i> Back to offering list</a>
                        </div>
                        <?php alerts() ?>
                        <?php $this->load->view('offering/form') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [data-expand] {
        display: none;
    }
    h2 .fa {
        color: #007fa4;
    }
    .fa-chevron-right {
        margin-right: 0.5rem;
    }
</style>
<?php 
$js = array(
    'https://www.google.com/recaptcha/api.js', 
    base_url('/js/selectize.js'),
    base_url('/js/offering-submit.js'),
    base_url('/js/bs-custom-file-input.js')
);
$css = array(
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css',
);
?>
<?php $this->load->view('footer-aim', compact('js', 'css')) ?>