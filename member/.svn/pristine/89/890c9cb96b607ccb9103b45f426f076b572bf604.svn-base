<?php $this->load->view('header-sya') ?>
<div class="page offering offering-form">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 py-5 py-5">
                        <h1>Best Practices Assessment</h1>
                        <p>Omaha STEM Ecosystem Best Practices Program Assessment Tool</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 py-5 mb-5">
                        <p>Thank you for completeing the Best Practices Assessment.</p>

                        <?php if ($results['neither']): ?>
                            <p>The offering does not meet best practices standards according to the Omaha STEM Ecosystem Best Practices Program Assessment Tool.</p>
                        <?php elseif ($results['developing']): ?>
                            <p>The offering meets <b>Developing</b> standards according to the Omaha STEM Ecosystem Best Practices Program Assessment Tool.</p>
                        <?php elseif ($results['proficient']): ?>
                            <p>The offering meets <b>Proficient</b> standards according to the Omaha STEM Ecosystem Best Practices Program Assessment Tool.</p>
                        <?php endif ?>

                        <br>
                        <p><a href="<?php echo base_url('offering') ?>" class="btn btn-outline-primary btn-sm ml-1">Back to Offerings Listing</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-aim') ?>