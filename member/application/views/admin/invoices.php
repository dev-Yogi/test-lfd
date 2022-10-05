<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Invoices
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/invoice/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> Create Invoice</a>
                    <?php alerts() ?>
                    <div class="card">
                        <?php $this->load->view('admin/invoices-list') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>