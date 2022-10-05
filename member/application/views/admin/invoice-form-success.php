<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Invoice Sent
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p>
                                The invoice has been emailed to the client, with a copy forwarded to you. <br>
                            </p>
                            <div class="form-group">
                                <label class="form-label">Invoice Status</label>
                                <div class="form-control-plaintext"><?php echo ucwords($invoice->status) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Recipient Name</label>
                                <div class="form-control-plaintext"><?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Recipient Email</label>
                                <div class="form-control-plaintext"><?php echo $invoice->email ?></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Product</label>
                                <div class="form-control-plaintext"><?php echo $product->label ?> - <?php echo ucwords($invoice->type) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total</label>
                                <div class="form-control-plaintext">$<?php echo number_format($invoice->amount, 2) ?></div>
                            </div>
                            <a href="<?php echo base_url('admin/dashboard') ?>" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>