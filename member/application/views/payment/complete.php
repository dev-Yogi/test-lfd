<?php $this->load->view('header-naked') ?>
<div class="page p-5">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Payment Complete
                        </h1>
                    </div>
                    <?php echo alerts() ?>
                    <div class="card p-5">
                        The payment has successfully been processed.
                        <div class="my-5"
                            <b>Transaction ID:</b> <?php echo $transaction['transaction_id'] ?>
                        </div>
                        <a href="<?php echo base_url('employer') ?>" class="btn btn-primary">Continue to Employer Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $js = array(base_url('js/payment.js')); ?>
<?php $this->load->view('footer-naked', compact('js')) ?>